const paths = new Set(['/demo-api/rules', '/demo-api/policy', '/demo-api/programs', '/demo-api/dates', '/demo-api/roster', '/demo-api/quote', '/demo-api/complete', '/demo-api/seed', '/demo-api/change']);
let context: (() => string) | null = null;
export function setDemoContext(provider: () => string): void { context = provider; }
let generation = 0;
let controller = new AbortController();
const cache = new Map<string, Promise<unknown>>();

export function demoGeneration(): number { return generation; }

export function resetDemoSession(): void {
  generation++;
  controller.abort();
  controller = new AbortController();
  cache.clear();
}

export async function requestDemo<T>(url: string, init: RequestInit = {}): Promise<{ body: T; status: number; ok: boolean }> {
  if (!paths.has(url)) throw new Error('Deze koppeling hoort niet bij de demo.');
  if (url === '/demo-api/dates') init = { ...init, method: 'POST', body: new FormData() };
  if (init.body instanceof FormData && context) {
    init.body.set('demoContext', context());
  }
  const requestGeneration = generation;
  const signal = AbortSignal.any([controller.signal, AbortSignal.timeout(15000), ...(init.signal ? [init.signal] : [])]);
  const headers = new Headers(init.headers);
  headers.set('Accept', 'application/json');
  const response = await fetch(url, { ...init, signal, headers, credentials: 'omit', cache: 'no-store', redirect: 'error', referrerPolicy: 'no-referrer' });
  const body = await response.json() as T;
  if (requestGeneration !== generation || signal.aborted) throw new DOMException('Demo opnieuw gestart.', 'AbortError');
  return { body, status: response.status, ok: response.ok };
}

export function cachedDemoData<T>(url: string, load: () => Promise<T>): Promise<T> {
  const existing = cache.get(url);
  if (existing) return existing as Promise<T>;
  const promise = load().catch((error: unknown) => {
    if (cache.get(url) === promise) cache.delete(url);
    throw error;
  });
  cache.set(url, promise);
  return promise;
}
