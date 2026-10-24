// Local rendering only. Never connects, installs, reloads or enables services.
import { readFileSync, readdirSync, mkdirSync, writeFileSync, existsSync } from 'node:fs';
import { resolve, dirname, join, relative, isAbsolute, sep } from 'node:path';
import { fileURLToPath } from 'node:url';

const here = dirname(fileURLToPath(import.meta.url));
const repo = resolve(here, '..');
const [configFile, output] = process.argv.slice(2);
if (!configFile || !output) throw Error('Usage: node deploy/render.mjs reviewed.json NEW_EXTERNAL_DIRECTORY');
const config = JSON.parse(readFileSync(configFile, 'utf8'));
if (config.reviewed !== true) throw Error('Review configuration first; reviewed must be true. This does not authorize activation.');
const expected = ['reviewed', 'phpSeries', 'siteRoot', 'demoRoot', 'siteCertificate', 'demoCertificate'];
if (Object.keys(config).some(key => !expected.includes(key)) || expected.some(key => !(key in config))) throw Error('Unexpected or missing configuration keys.');
if (config.phpSeries !== '8.5') throw Error('Only the inventoried 8.5 series is prepared.');
if (config.siteRoot !== '/var/www/kevin-webstudio' || config.demoRoot !== '/var/www/onderwijsdemo') throw Error('Review all paths and isolation before changing the proposed roots.');
for (const key of ['siteCertificate', 'demoCertificate']) {
  if (!/^[a-z0-9][a-z0-9.-]*$/.test(config[key])) throw Error(`Missing or invalid ${key}.`);
}
const out = resolve(output), rel = relative(repo, out);
if (!(rel === '..' || rel.startsWith('..' + sep) || isAbsolute(rel))) throw Error('Output must be outside the repository.');
if (existsSync(out)) throw Error('Output must be a new directory; nothing is overwritten.');
const replacements = {
  SITE_ROOT: config.siteRoot, DEMO_ROOT: config.demoRoot, PHP_SERIES: config.phpSeries,
  SITE_CERT: config.siteCertificate, DEMO_CERT: config.demoCertificate,
};
const rendered = readdirSync(join(here, 'templates')).filter(name => name.endsWith('.tpl')).map(name => {
  const content = readFileSync(join(here, 'templates', name), 'utf8').replace(/@@([A-Z_]+)@@/g, (_, key) => {
    if (!(key in replacements)) throw Error(`Unknown token: ${key}`);
    return replacements[key];
  });
  if (content.includes('@@')) throw Error(`Unresolved template: ${name}`);
  return [name.slice(0, -4), content];
});
mkdirSync(out, { recursive: true });
for (const [name, content] of rendered) writeFileSync(join(out, name), content, { flag: 'wx' });
console.log(`Rendered ${rendered.length} files. Review only; nothing activated.`);
