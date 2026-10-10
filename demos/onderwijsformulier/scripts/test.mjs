import { execFileSync } from 'node:child_process';
import { readdirSync } from 'node:fs';
import { fileURLToPath } from 'node:url';

const root = fileURLToPath(new URL('../', import.meta.url));
const php = process.env.DEMO_PHP || 'C:/wamp64/bin/php/php8.4.15/php.exe';
function files(dir) {
  return readdirSync(root + dir, { withFileTypes: true }).flatMap(entry => entry.isDirectory() ? files(`${dir}/${entry.name}`) : [`${dir}/${entry.name}`]);
}
const phpFiles = ['src', 'server', 'tests'].flatMap(files).filter(file => file.endsWith('.php'));
for (const file of phpFiles) execFileSync(php, ['-l', file], { cwd: root, stdio: 'pipe' });
console.log(`PHP syntax: ${phpFiles.length} bestanden in orde.`);
execFileSync(php, ['-d', 'display_errors=1', '-d', 'log_errors=0', '-d', 'disable_functions=mail', 'tests/domain.php'], { cwd: root, stdio: 'inherit' });
