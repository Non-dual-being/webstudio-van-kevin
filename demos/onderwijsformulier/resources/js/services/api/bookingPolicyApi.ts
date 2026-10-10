import type { BoekingBeleidApiResponse } from '../../types/booking/BookingPolicyTypes';
import { getApiData } from '../http/apiClient';
import { cachedDemoData } from '../http/demoSession';

export function fetchBookingPolicyRules(): Promise<BoekingBeleidApiResponse> {
  return cachedDemoData('/demo-api/policy', () => getApiData<BoekingBeleidApiResponse>('/demo-api/policy'));
}
