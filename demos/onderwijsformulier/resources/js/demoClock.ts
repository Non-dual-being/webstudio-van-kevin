let today: string | null = null;

export function setDemoToday(value: string | null): void { today = value; }

export function demoToday(): Date {
  if (today === null) throw new Error('De voorbeeldkalender is nog niet geladen.');
  return new Date(`${today}T00:00:00`);
}
