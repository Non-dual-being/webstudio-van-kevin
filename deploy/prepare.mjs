// Build a committed release OUTSIDE the checkout. No SSH, deployment or live .env.
import { execFileSync } from 'node:child_process';
import { existsSync, mkdirSync, cpSync, writeFileSync, readdirSync, readFileSync } from 'node:fs';
import { resolve, relative, isAbsolute, dirname, join, sep } from 'node:path';
import { fileURLToPath } from 'node:url';
import { createHash } from 'node:crypto';

const repo = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const [sha, destination, php] = process.argv.slice(2);
if (!/^[a-f0-9]{40}$/.test(sha || '') || !destination || !php) throw Error('Usage: node deploy/prepare.mjs FULL_REVIEWED_SHA NEW_EXTERNAL_DIRECTORY PHP_8_4_15');
const out = resolve(destination), rel = relative(repo, out);
if (!(rel === '..' || rel.startsWith('..' + sep) || isAbsolute(rel)) || existsSync(out)) throw Error('Use a new directory outside the checkout.');
const read = (exe, args, cwd = repo) => execFileSync(exe, args, { cwd, encoding: 'utf8', windowsHide: true }).trim();
if (read('git', ['rev-parse', `${sha}^{commit}`]) !== sha) throw Error('Commit unavailable.');
if (process.versions.node !== '22.23.1') throw Error('Use isolated Node 22.23.1; do not replace global tools.');
if (read(php, ['-r', 'echo PHP_VERSION;']) !== '8.4.15') throw Error('Local build validation requires PHP 8.4.15.');
mkdirSync(out, { recursive: true });
const source = join(out, 'source');
mkdirSync(source);
execFileSync('git', ['archive', '--format=tar', `--output=${join(out, 'source.tar')}`, sha], { cwd: repo, windowsHide: true });
execFileSync('tar', ['-xf', join(out, 'source.tar'), '-C', source], { windowsHide: true });
if (existsSync(join(source, '.env'))) throw Error('A committed environment file is forbidden.');
const storage = join(out, 'build-storage');
for (const p of ['framework/cache/data', 'framework/sessions', 'framework/views', 'logs']) mkdirSync(join(storage, p), { recursive: true });
const env = { ...process.env, PATH: dirname(resolve(php)) + (process.platform === 'win32' ? ';' : ':') + process.env.PATH,
  APP_ENV: 'testing', APP_DEBUG: 'false', APP_URL: 'https://kevinwebstudio.nl',
  APP_KEY: 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=',
  DB_CONNECTION: 'sqlite', DB_DATABASE: ':memory:', DB_URL: '', CACHE_STORE: 'array',
  CONTACT_CACHE_STORE: 'array', QUEUE_CONNECTION: 'sync', SESSION_DRIVER: 'array',
  MAIL_MAILER: 'array', MAIL_URL: 'null', MAIL_HOST: '127.0.0.1', MAIL_PORT: '1', MAIL_PASSWORD: '',
  CONTACT_ALLOW_TEST_TRANSPORT: 'true', LARAVEL_STORAGE_PATH: storage,
  APP_CONFIG_CACHE: relative(source, join(out, 'unused-config.php')), APP_ROUTES_CACHE: relative(source, join(out, 'unused-routes.php')),
  LOG_CHANNEL: 'null', VITE_WEBSTUDIO_LINK_ENABLED: 'true', EDUCATION_DEMO_ENABLED: 'false',
};
const run = (exe, args, cwd = source, capture = false) => execFileSync(exe, args, {
  cwd, env, windowsHide: true, shell: process.platform === 'win32' && /\.(cmd|bat)$/.test(exe),
  stdio: capture ? 'pipe' : 'inherit', encoding: 'utf8',
});
const pnpm = process.platform === 'win32' ? 'pnpm.cmd' : 'pnpm';
const npm = process.platform === 'win32' ? 'npm.cmd' : 'npm';
const composer = process.platform === 'win32' ? 'composer.bat' : 'composer';
if (run(pnpm, ['--version'], source, true).trim() !== '11.5.2' || run(npm, ['--version'], source, true).trim() !== '12.0.2') throw Error('Use local pnpm 11.5.2 / npm 12.0.2 in the build environment.');
run(composer, ['install', '--no-dev', '--prefer-dist', '--no-interaction', '--no-scripts', '--optimize-autoloader']);
run(composer, ['check-platform-reqs', '--lock', '--no-dev']);
run(php, ['artisan', 'package:discover', '--no-interaction']);
run(pnpm, ['install', '--frozen-lockfile']);
run(pnpm, ['build']);
const demo = join(source, 'demos/onderwijsformulier');
run(npm, ['ci'], demo);
run(npm, ['run', 'build'], demo);
const siteRuntime = join(out, 'site'), demoRuntime = join(out, 'demo');
mkdirSync(siteRuntime); mkdirSync(demoRuntime);
for (const name of ['app', 'config', 'routes', 'vendor', 'artisan', 'composer.json', 'composer.lock', 'public', 'deploy']) cpSync(join(source, name), join(siteRuntime, name), { recursive: true });
cpSync(join(source, 'bootstrap/app.php'), join(siteRuntime, 'bootstrap/app.php'), { recursive: true });
cpSync(join(source, 'bootstrap/providers.php'), join(siteRuntime, 'bootstrap/providers.php'));
mkdirSync(join(siteRuntime, 'bootstrap/cache'));
cpSync(join(source, 'resources/views'), join(siteRuntime, 'resources/views'), { recursive: true });
cpSync(join(source, 'database/migrations'), join(siteRuntime, 'database/migrations'), { recursive: true });
for (const name of ['dist', 'server', 'src']) cpSync(join(demo, name), join(demoRuntime, name), { recursive: true });
// Remove nothing from the workspace. Private runtime state is never copied.
for (const name of ['.env', 'public/hot', 'public/storage', 'storage', 'database/database.sqlite']) {
  if (existsSync(join(siteRuntime, name))) throw Error(`Unexpected runtime state: ${name}`);
}
const walk = path => readdirSync(path, { withFileTypes: true }).flatMap(e => e.isDirectory() ? walk(join(path, e.name)) : [join(path, e.name)]);
const hashes = {};
for (const root of [siteRuntime, demoRuntime]) for (const file of walk(root)) hashes[relative(out, file).replaceAll('\\', '/')] = createHash('sha256').update(readFileSync(file)).digest('hex');
writeFileSync(join(out, 'manifest.json'), JSON.stringify({ commit: sha, node: process.versions.node, php: '8.4.15', pnpm: '11.5.2', npm: '12.0.2', hashes }, null, 2) + '\n');
for (const name of ['site', 'demo']) execFileSync('tar', ['-czf', join(out, `${name}.tar.gz`), '-C', join(out, name), '.'], { windowsHide: true });
console.log('Release artifacts prepared locally. Linux PHP 8.5/FPM, rights and live checks remain release gates.');
