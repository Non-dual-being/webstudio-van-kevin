import type { fullDatesInfo } from '../../types/booking/BookingDateType';
import { getApiData } from '../http/apiClient';
import { cachedDemoData } from '../http/demoSession';

export function fetchDisabledDates(): Promise<fullDatesInfo> {
  return cachedDemoData('/demo-api/dates', () => getApiData<fullDatesInfo>('/demo-api/dates'));
}
