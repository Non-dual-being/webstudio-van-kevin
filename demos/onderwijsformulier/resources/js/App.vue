<script setup lang="ts">
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import { Check, GraduationCap, RotateCcw } from '@lucide/vue';
import BookingGeoFormView from './views/BookingGeoFormView.vue';
import DashboardView from './dashboard/DashboardView.vue';
import { initializeValidationRules, clearValidationRules } from './config/validation/booking';
import { fetchBookingPolicyRules } from './services/api/bookingPolicyApi';
import { fetchBookingProgramConfigValues } from './services/api/bookingProgramConfigApi';
import { fetchDisabledDates } from './services/api/bookingDisabledDatesApi';
import { resetDemoSession } from './services/http/demoSession';
import { setDemoToday } from './demoClock';
import { addRecord, lastCreated, loadSeed } from './dashboard/state';
import type { ApiOk } from './types/http/ApiResponse';
import '../css/dashboard.css';

const run = ref(0), ready = ref(false), completed = ref(false), error = ref('');
const heading = ref<HTMLElement | null>(null), success = ref<HTMLElement | null>(null);
const route = ref(location.hash || '#/formulier');
const dashboard = computed(() => route.value.startsWith('#/aanvragen'));
const validRoute = computed(() => dashboard.value || route.value === '#/formulier');
function navigate(): void {
  const next = location.hash || '#/formulier';
  if (next === '#/formulier' && dashboard.value) completed.value = false;
  route.value = next;
}
async function start(focus = true): Promise<void> {
  const current = ++run.value;
  ready.value = false; completed.value = false; error.value = '';
  resetDemoSession();
  await nextTick();
  clearValidationRules(); setDemoToday(null);
  if (focus) { heading.value?.focus(); window.scrollTo({ top: 0, behavior: 'instant' }); }
  try {
    await loadSeed();
    const [, , , dates] = await Promise.all([initializeValidationRules(), fetchBookingPolicyRules(), fetchBookingProgramConfigValues(), fetchDisabledDates()]);
    if (current === run.value) { setDemoToday(dates.minDate); ready.value = true; }
  } catch {
    if (current === run.value) error.value = 'De demo kon niet laden. Controleer of de lokale demoserver draait en probeer opnieuw.';
  }
}
async function complete(result: ApiOk): Promise<void> {
  addRecord(result.record); completed.value = true;
  await nextTick();
  success.value?.focus(); window.scrollTo({ top: 0, behavior: 'instant' });
}
function newForm(): void { completed.value = false; location.hash = '/formulier'; }
onMounted(() => { window.addEventListener('hashchange', navigate); void start(false); });
onBeforeUnmount(() => window.removeEventListener('hashchange', navigate));
</script>
<template>
  <header class="demo-intro" :class="{ 'demo-intro--dashboard': dashboard }" id="demo-uitleg">
    <div class="demo-brand"><GraduationCap aria-hidden="true" :size="22" /><span>Kevin Webstudio · interactieve demo</span></div>
    <template v-if="!dashboard"><p class="demo-eyebrow">Onderwijsbezoek</p><h1 ref="heading" tabindex="-1">Van eerste keuze<br />tot helder overzicht.</h1><p class="demo-lead">Probeer hoe een formulier meedenkt. Kies een datum, stel uw programma samen en bekijk direct de voorbeeldprijs.</p></template>
    <h1 v-else ref="heading" class="dashboard-brand-title" tabindex="-1">Onderwijsbezoeken</h1>
    <div class="demo-notice"><strong>Interactieve demo — fictieve scholen.</strong> Je wijzigingen gelden alleen voor jouw demonstratieomgeving. Er wordt niets echt geboekt of gemaild. Vul geen echte persoonsgegevens in.</div>
    <nav class="demo-navigation" aria-label="Demo"><a class="demo-nav-link" href="#/formulier" :aria-current="!dashboard ? 'page' : undefined">Aanvraagformulier</a><a class="demo-nav-link" href="#/aanvragen" :aria-current="dashboard ? 'page' : undefined">Demodashboard</a><button class="demo-reset" type="button" @click="start()"><RotateCcw :size="17" aria-hidden="true" />Demo opnieuw starten</button></nav>
  </header>
  <section v-if="error" class="demo-message" role="alert"><h2>Even opnieuw proberen</h2><p>{{ error }}</p><button class="demo-reset" @click="start()">Opnieuw proberen</button></section>
  <p v-else-if="!ready" class="demo-message" role="status">De voorbeeldkalender en formulierregels worden geladen…</p>
  <DashboardView v-else-if="dashboard" :key="run" :route="route" @new-form="newForm" />
  <section v-else-if="!validRoute" class="demo-message"><h2>Deze demopagina bestaat niet.</h2><a href="#/aanvragen">Naar het overzicht</a></section>
  <section v-else-if="completed" ref="success" class="demo-message demo-success" role="status" tabindex="-1"><Check :size="28" aria-hidden="true" /><h2>Demo afgerond.</h2><p>Je demoaanvraag staat in jouw demonstratieomgeving. Er is geen echte boeking gemaakt of e-mail verstuurd.</p><p class="demo-muted">Je wijzigingen blijven beschikbaar zolang dit tabblad openstaat. Vernieuwen of opnieuw starten herstelt de voorbeelden.</p><a class="demo-reset" :href="'#/aanvragen/' + lastCreated">Bekijk in het demodashboard</a><button class="demo-reset" @click="newForm">Nog een demoaanvraag</button></section>
  <BookingGeoFormView v-else :key="run" @success="complete" @server-error="error = $event.message" />
  <footer class="demo-footer">Fictieve prijzen en aantallen. Je wijzigingen blijven in dit tabblad tot vernieuwen of opnieuw starten. Geen echte boeking of e-mail.</footer>
</template>
