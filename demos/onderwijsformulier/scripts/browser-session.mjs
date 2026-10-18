import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import net from 'node:net';
import { spawn } from 'node:child_process';
import { fileURLToPath } from 'node:url';
import { startPhp } from './php-server.mjs';

export const root = fileURLToPath(new URL('../', import.meta.url));
export const origin = 'http://127.0.0.1:8121';
export const pause = ms => new Promise(resolve => setTimeout(resolve, ms));

export async function launch({ startServer = true } = {}) {
  if (!fs.existsSync(root + '/dist/index.html')) throw Error('Eerst npm run build; tests gebruiken de voltooide build.');
  if (startServer) await new Promise((resolve, reject) => {
    const server = net.createServer();
    server.on('error', reject);
    server.listen(8121, '127.0.0.1', () => server.close(resolve));
  });
  const profile = fs.mkdtempSync(path.join(os.tmpdir(), 'kevin-onderwijs-browser-'));
  const server = startServer ? startPhp(8121, '2031-10-06') : null;
  const chrome = spawn(process.env.DEMO_CHROME || 'C:/Program Files/Google/Chrome/Application/chrome.exe', [
    '--headless=new', '--remote-debugging-address=127.0.0.1', '--remote-debugging-port=0', '--user-data-dir=' + profile,
    '--no-first-run', '--no-default-browser-check', '--disable-sync', '--disable-background-networking', 'about:blank',
  ], { windowsHide: true, stdio: 'ignore' });
  const stop = async () => {
    // Alleen de processen die door deze test zijn gestart; geen bestaande browser/server.
    server?.kill(); chrome.kill();
  };
  let port;
  try {
    for (let attempt = 0; attempt < 100; attempt++) {
      if (fs.existsSync(profile + '/DevToolsActivePort')) port = fs.readFileSync(profile + '/DevToolsActivePort', 'utf8').split('\n')[0];
      try { if (port && (await fetch(origin + '/demo-api/policy')).ok) break; } catch { /* Server start nog. */ }
      await pause(100);
    }
    if (!port) throw Error('Test-Chrome kon niet starten.');
    return { port, stop };
  } catch (error) { await stop(); throw error; }
}

export async function tab(port) {
  const target = await fetch(`http://127.0.0.1:${port}/json/new?about:blank`, { method: 'PUT' }).then(r => r.json());
  const socket = new WebSocket(target.webSocketDebuggerUrl);
  await new Promise((resolve, reject) => { socket.onopen = resolve; socket.onerror = reject; });
  let id = 0;
  const pending = new Map(), errors = [], requests = [], listeners = new Map();
  socket.onmessage = event => {
    const message = JSON.parse(event.data);
    if (message.id) {
      const p = pending.get(message.id); pending.delete(message.id);
      if (message.error) p.reject(Error(JSON.stringify(message.error))); else p.resolve(message.result);
    } else {
      if (message.method === 'Runtime.exceptionThrown') errors.push(message.params.exceptionDetails.text);
      if (message.method === 'Network.requestWillBeSent') {
        const url = new URL(message.params.request.url);
        requests.push({ origin: url.origin, path: url.pathname, method: message.params.request.method });
      }
      listeners.get(message.method)?.(message.params);
    }
  };
  const send = (method, params = {}) => new Promise((resolve, reject) => { const seq = ++id; pending.set(seq, { resolve, reject }); socket.send(JSON.stringify({ id: seq, method, params })); });
  const evaluate = async expression => {
    const result = await send('Runtime.evaluate', { expression, returnByValue: true, awaitPromise: true });
    if (result.exceptionDetails) throw Error(JSON.stringify(result.exceptionDetails));
    return result.result.value;
  };
  const until = async expression => {
    for (let attempt = 0; attempt < 120; attempt++) { if (await evaluate(expression)) return; await pause(100); }
    throw Error('Timeout: ' + expression);
  };
  const key = async (name, modifiers = 0) => {
    const codes = { Tab: 9, Enter: 13, Escape: 27, ArrowDown: 40, ArrowUp: 38, ArrowLeft: 37, ArrowRight: 39, Home: 36, End: 35, ' ': 32, a: 65, Backspace: 8 };
    for (const type of ['keyDown', 'keyUp']) await send('Input.dispatchKeyEvent', { type, key: name, code: name === ' ' ? 'Space' : name === 'a' ? 'KeyA' : name, windowsVirtualKeyCode: codes[name], modifiers, ...(type === 'keyDown' && ['Enter', ' '].includes(name) ? { text: name === 'Enter' ? '\r' : ' ', unmodifiedText: name === 'Enter' ? '\r' : ' ' } : {}) });
  };
  const tabTo = async selector => {
    for (let attempt = 0; attempt < 160; attempt++) {
      if (await evaluate(`document.activeElement.matches(${JSON.stringify(selector)})`)) return;
      await key('Tab');
    }
    throw Error('Niet bereikbaar met Tab: ' + selector);
  };
  const type = async (selector, text) => { await tabTo(selector); await key('a', 2); await send('Input.insertText', { text }); await key('Tab'); };
  const shot = async (file, full = false) => {
    await evaluate('document.fonts.ready'); await pause(700);
    const options = { format: 'png', captureBeyondViewport: full };
    if (full) { const metrics = await send('Page.getLayoutMetrics'); options.clip = { x: 0, y: 0, width: metrics.cssContentSize.width, height: metrics.cssContentSize.height, scale: 1 }; }
    const { data } = await send('Page.captureScreenshot', options);
    fs.mkdirSync(path.dirname(file), { recursive: true });
    fs.writeFileSync(file, Buffer.from(data, 'base64'));
  };
  await send('Page.enable'); await send('Runtime.enable'); await send('Network.enable');
  return { send, evaluate, until, key, tabTo, type, shot, errors, requests, listeners, close: async () => { await send('Page.close'); socket.close(); } };
}
