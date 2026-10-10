import { spawn, execFileSync } from 'node:child_process';
import { existsSync } from 'node:fs';
import { fileURLToPath } from 'node:url';

const root = fileURLToPath(new URL('../', import.meta.url));

export function startPhp(port, today = process.env.DEMO_TODAY) {
  const php = process.env.DEMO_PHP || 'C:/wamp64/bin/php/php8.4.15/php.exe';
  if (!Number.isInteger(port) || port < 1024 || port > 65535) throw Error('Ongeldige DEMO_PORT.');
  if (execFileSync(php, ['-r', 'echo PHP_VERSION;'], { encoding: 'utf8' }).trim() !== '8.4.15') throw Error('Gebruik PHP 8.4.15 via DEMO_PHP.');
  if (!existsSync(root + '/dist/index.html')) throw Error('Voer eerst npm run build uit.');
  const env = { ...process.env };
  if (today) env.DEMO_TODAY = today; else delete env.DEMO_TODAY;
  return spawn(php, ['-d', 'display_errors=0', '-d', 'log_errors=0', '-d', 'session.auto_start=0', '-d', 'file_uploads=0', '-d', 'post_max_size=32K', '-d', 'disable_functions=mail,exec,passthru,shell_exec,system,proc_open,popen', '-S', `127.0.0.1:${port}`, '-t', root + '/dist', root + '/server/router.php'], {
    cwd: root, env, windowsHide: true,
    // Geen request-URL's of formuliergegevens naar console/logbestanden.
    stdio: ['ignore', 'ignore', 'ignore'],
  });
}
