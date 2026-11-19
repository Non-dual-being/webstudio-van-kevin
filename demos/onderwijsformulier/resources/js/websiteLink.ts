// Build-time choice only. No visitor input, API destination or arbitrary origin.
export const websiteCaseUrl = import.meta.env.MODE === 'local-preview'
  ? 'http://127.0.0.1:8136/werk/onderwijsformulier'
  : 'https://kevinwebstudio.nl/werk/onderwijsformulier';
