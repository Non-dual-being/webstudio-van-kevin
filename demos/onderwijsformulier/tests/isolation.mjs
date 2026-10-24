import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const root = fileURLToPath(new URL('../', import.meta.url));
const files = dir => fs.readdirSync(root + dir, {withFileTypes:true}).flatMap(e=>e.isDirectory()?files(dir+'/'+e.name):[dir+'/'+e.name]);
const php = [...files('server'),...files('src')].filter(f=>f.endsWith('.php'));
const frontend = files('resources/js').filter(f=>/\.(vue|ts)$/.test(f));
for (const file of php) {
  const code = fs.readFileSync(root+file,'utf8');
  assert(!/\b(?:PDO|mysqli|PHPMailer)\b|Services\\Sql\\|\b(?:mail|error_log|file_put_contents|fopen|session_start|curl_init)\s*\(/.test(code), `Opslag/mail/logconnector: ${file}`);
  assert(!/vendor\/autoload|bootstrap\/app|Dotenv|\.env/.test(code.replace(/^\s*\/\/.*$/gm,'')), `Oorspronkelijke bootstrap: ${file}`);
}
for (const file of frontend) {
  const code = fs.readFileSync(root+file,'utf8');
  // One explicitly gated navigation link; never an API/asset/mail destination.
  const backLink = '<p v-if="showWebsiteLink"><a class="demo-nav-link" href="https://kevinwebstudio.nl/werk/onderwijsformulier">Terug naar Kevin Webstudio</a></p>';
  const externalCode = file === 'resources/js/App.vue' ? code.replace(backLink, '') : code;
  if (file === 'resources/js/App.vue') {
    assert(code.includes(backLink), 'Teruglink heeft vaste URL, eigen tabblad en releaseflag');
    assert(code.includes("const showWebsiteLink = import.meta.env.VITE_WEBSTUDIO_LINK_ENABLED === 'true';"), 'Teruglink standaard uit');
  }
  assert(!/localStorage|sessionStorage|indexedDB|document\.cookie|history\.(pushState|replaceState)|serviceWorker/.test(code), `Browseropslag: ${file}`);
  assert(!/https?:\/\/|mailto:|geofort\.nl|GeoFort_Onderwijs\.pdf/.test(externalCode), `Oorspronkelijke of externe koppeling: ${file}`);
  if (/\bfetch\(/.test(code)) assert(file.endsWith('/demoSession.ts'), `Fetch buiten demo-API: ${file}`);
}
assert(!files('src').some(f=>/Sql|SubmissionService|Mailer/.test(f)), 'Onnodige infrastructuur overgenomen');
assert(fs.readFileSync(root+'server/router.php','utf8').includes("connect-src 'self'"), 'Same-origin CSP');
assert(fs.readFileSync(root+'scripts/php-server.mjs','utf8').includes('disable_functions=mail'), 'Mail moet technisch uit staan');
assert(!fs.existsSync(path.join(root,'vendor')), 'Demo vereist geen Laravel/vendor');
console.log(`OK: isolatiecontrole van ${php.length} PHP- en ${frontend.length} frontendbestanden.`);
