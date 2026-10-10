import type { BookingProgramConfigData } from '../../types/booking/BookingProgramConfigTypes';
import { getApiData } from '../http/apiClient';
import { cachedDemoData } from '../http/demoSession';

export function fetchBookingProgramConfigValues(): Promise<BookingProgramConfigData> {
  return cachedDemoData('/demo-api/programs', () => getApiData<BookingProgramConfigData>('/demo-api/programs'));
}
