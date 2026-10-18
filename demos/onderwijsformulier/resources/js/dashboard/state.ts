import { shallowRef } from 'vue';
import type { BookingPriceQuoteDto } from '../types/booking/BookingPriceQuoteTypes';
import type { BookingStatus } from '../admin/types/bookingStatus';
import { requestDemo, resetDemoSession, setDemoContext } from '../services/http/demoSession';

export interface DemoRecord {
  id: number;
  status: BookingStatus;
  payload: Record<string, string>;
  quote: BookingPriceQuoteDto;
  sectorLabel: string;
  programLabel: string;
  moduleLabel: string;
  educationLabels: string[];
  warnings: string[];
}

export const records = shallowRef<DemoRecord[]>([]);
export const lastCreated = shallowRef<number | null>(null);
setDemoContext(() => JSON.stringify(records.value.map(r => ({ id: r.id, date: r.payload.bezoekdatum, students: Number(r.payload.aantalLeerlingen), status: r.status }))));

export async function loadSeed(): Promise<void> {
  records.value = [];
  lastCreated.value = null;
  const response = await requestDemo<{ ok: boolean; data: DemoRecord[] }>('/demo-api/seed');
  if (!response.ok || !response.body.ok) throw Error('De voorbeeldgegevens konden niet laden.');
  records.value = response.body.data;
}

export function addRecord(record: DemoRecord): void {
  if (!records.value.some(r => r.id === record.id)) records.value = [...records.value, record];
  lastCreated.value = record.id;
  resetDemoSession(); // Kalender/prijs-caches vervallen, de records blijven in het tabblad.
}

export async function changeRecord(record: DemoRecord, action: 'edit' | 'status', values: Record<string, string>): Promise<void> {
  const body = new FormData();
  body.set('record', JSON.stringify({ id: record.id, status: record.status, payload: record.payload }));
  body.set('action', action);
  if (action === 'edit') body.set('changes', JSON.stringify(Object.fromEntries(Object.entries(values).map(([key, value]) => [key, String(value)]))));
  else body.set('target', values.target ?? '');
  const response = await requestDemo<{ ok: boolean; data: DemoRecord; fieldErrors?: Record<string, string> }>('/demo-api/change', { method: 'POST', body });
  if (!response.ok || !response.body.ok) throw new Error(Object.values(response.body.fieldErrors ?? {}).join(' ') || 'De wijziging kon niet worden gecontroleerd. Probeer opnieuw.');
  if (!records.value.includes(record)) throw new DOMException('Verouderde aanvraag.', 'AbortError');
  records.value = records.value.map(r => r.id === record.id ? response.body.data : r);
  resetDemoSession();
}
