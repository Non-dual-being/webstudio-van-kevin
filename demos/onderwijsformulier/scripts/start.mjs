import { startPhp } from './php-server.mjs';

const port = Number(process.env.DEMO_PORT || 8120);
const server = startPhp(port);
server.on('error', () => { console.error('Demoserver kon niet starten. Controleer PHP en de poort.'); process.exitCode = 1; });
server.on('exit', (code) => { process.exitCode = code || 0; });
for (const signal of ['SIGINT', 'SIGTERM']) process.on(signal, () => server.kill());
console.log(`Onderwijsdemo: http://127.0.0.1:${port} — uitsluitend fictieve gegevens. Stop met Ctrl+C.`);
