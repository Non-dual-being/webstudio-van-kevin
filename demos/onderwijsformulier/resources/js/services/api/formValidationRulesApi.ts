import type { FrontendFormRules } from '../../config/validation/booking';
import { getApiData } from '../http/apiClient';
import { cachedDemoData } from '../http/demoSession';

export function fetchFormValidationRules(): Promise<FrontendFormRules> {
  return cachedDemoData('/demo-api/rules', () => getApiData<FrontendFormRules>('/demo-api/rules'));
}
