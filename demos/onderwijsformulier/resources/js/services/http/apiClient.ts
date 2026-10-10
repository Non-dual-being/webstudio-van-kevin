import type { ApiDataFetch } from '../../types/http/ApiResponse';
import { requestDemo } from './demoSession';

export class ApiError extends Error {
  constructor(message: string, public readonly status: number) { super(message); this.name = 'ApiError'; }
}

export async function getApiData<T>(url: string, init: RequestInit = {}): Promise<T> {
  try {
    const response = await requestDemo<ApiDataFetch<T>>(url, init);
    if (!response.ok || !response.body?.ok) throw new ApiError('De demo kon dit niet laden. Probeer opnieuw of start de demo opnieuw.', response.status);
    return response.body.data;
  } catch (error) {
    if (error instanceof Error && (error.name === 'AbortError' || error instanceof ApiError)) throw error;
    throw new ApiError('De demo kon dit niet laden. Controleer de verbinding en probeer opnieuw.', 502);
  }
}
