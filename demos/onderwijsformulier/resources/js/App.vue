<script setup lang="ts">
import { nextTick, onMounted, ref } from 'vue';
import { Check, GraduationCap, RotateCcw } from '@lucide/vue';
import BookingGeoFormView from './views/BookingGeoFormView.vue';
import { initializeValidationRules, clearValidationRules } from './config/validation/booking';
import { fetchBookingPolicyRules } from './services/api/bookingPolicyApi';
import { fetchBookingProgramConfigValues } from './services/api/bookingProgramConfigApi';
import { fetchDisabledDates } from './services/api/bookingDisabledDatesApi';
import { resetDemoSession } from './services/http/demoSession';
import { setDemoToday } from './demoClock';

const run = ref(0);
const ready = ref(false);
const completed = ref(false);
const error = ref('');
const heading = ref<HTMLElement | null>(null);
const success = ref<HTMLElement | null>(null);

async function start(focus = true): Promise<void> {
  const current = ++run.value;
  ready.value = false;
  completed.value = false;
  error.value = '';
  resetDemoSession();
  await nextTick(); // Eerst alle oude veld-, kalender- en timerinstanties opruimen.
  clearValidationRules();
  setDemoToday(null);
  if (focus) { heading.value?.focus(); window.scrollTo({ top: 0, behavior: 'instant' }); }
  try {
    const [, , , dates] = await Promise.all([initializeValidationRules(), fetchBookingPolicyRules(), fetchBookingProgramConfigValues(), fetchDisabledDates()]);
    if (current === run.value) {
      setDemoToday(dates.minDate);
      ready.value = true;
    }
  } catch {
    if (current === run.value) error.value = 'De demo kon niet laden. Controleer of de lokale demoserver draait en probeer opnieuw.';
  }
}

async function complete(): Promise<void> {
  completed.value = true;
  ready.value = false;
  resetDemoSession();
  await nextTick();
  clearValidationRules();
  success.value?.focus();
  window.scrollTo({ top: 0, behavior: 'instant' });
}

onMounted(() => { void start(false); });
</script>

<template>
  <header class="demo-intro" id="demo-uitleg">
    <div class="demo-brand"><GraduationCap aria-hidden="true" :size="22" /><span>Kevin Webstudio · interactieve demo</span></div>
    <p class="demo-eyebrow">Onderwijsbezoek</p>
    <h1 ref="heading" tabindex="-1">Van eerste keuze<br />tot helder overzicht.</h1>
    <p class="demo-lead">Probeer hoe een formulier meedenkt. Kies een datum, stel uw programma samen en bekijk direct de voorbeeldprijs.</p>
    <div class="demo-notice"><strong>Vrij uitproberen met fictieve gegevens.</strong> Beschikbaarheid en prijzen zijn voorbeelden. Er wordt niets geboekt, opgeslagen of gemaild. Vul geen echte persoonsgegevens in.</div>
    <button class="demo-reset" type="button" @click="start()"><RotateCcw :size="17" aria-hidden="true" />Demo opnieuw starten</button>
  </header>

  <section v-if="completed" ref="success" class="demo-message demo-success" role="status" tabindex="-1">
    <Check :size="28" aria-hidden="true" />
    <h2>Demo afgerond.</h2>
    <p>Er is geen aanvraag opgeslagen of e-mail verstuurd.</p>
    <p class="demo-muted">Probeer gerust een andere datum, onderwijssector of groepsgrootte.</p>
  </section>
  <section v-else-if="error" class="demo-message" role="alert"><h2>Even opnieuw proberen</h2><p>{{ error }}</p><button class="demo-reset" @click="start()">Opnieuw proberen</button></section>
  <BookingGeoFormView v-else-if="ready" :key="run" @success="complete" @server-error="error = $event.message" />
  <p v-else class="demo-message" role="status">De voorbeeldkalender en formulierregels worden geladen…</p>

  <footer class="demo-footer">Een demonstratie van een formulier met voorwaardelijke keuzes. Alleen de gegevens in dit open tabblad zijn tijdelijk beschikbaar.</footer>
</template>
