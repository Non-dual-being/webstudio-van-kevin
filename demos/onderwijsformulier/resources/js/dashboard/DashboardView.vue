<script setup lang="ts">
import { computed, nextTick, onBeforeUnmount, reactive, ref, watch } from 'vue';
import { ArrowLeft, ArrowUpRight, ClipboardList, Search } from '@lucide/vue';
import AdminButton from '../admin/components/form/AdminButton.vue';
import AdminNumberField from '../admin/components/form/AdminNumberField.vue';
import { BOOKING_STATUSES } from '../admin/types/bookingStatus';
import type { DashboardBookingFilters } from '../admin/types/bookings';
import { changeRecord, lastCreated, records } from './state';

const props = defineProps<{ route: string }>();
defineEmits<{ 'new-form': [] }>();
const defaults: DashboardBookingFilters = { search:'',status:'',sector:'',program:'',module:'',dateFrom:'',dateTo:'',page:1,sort:'desc' };
const filters = reactive({ ...defaults });
const dateError = computed(() => filters.dateFrom && filters.dateTo && filters.dateFrom > filters.dateTo ? 'De einddatum mag niet vóór de begindatum liggen.' : '');
const filtered = computed(() => dateError.value ? [] : records.value.filter(r =>
  r.payload.schoolnaam.toLocaleLowerCase('nl').includes(filters.search.trim().toLocaleLowerCase('nl')) &&
  (!filters.status || r.status === filters.status) && (!filters.program || r.payload.programma === filters.program) &&
  (!filters.dateFrom || r.payload.bezoekdatum >= filters.dateFrom) && (!filters.dateTo || r.payload.bezoekdatum <= filters.dateTo)
).sort((a,b) => (a.payload.bezoekdatum.localeCompare(b.payload.bezoekdatum) || a.id - b.id) * (filters.sort === 'asc' ? 1 : -1)));
const pages = computed(() => Math.max(1, Math.ceil(filtered.value.length / 20)));
const pageRows = computed(() => filtered.value.slice((filters.page - 1) * 20, filters.page * 20));
const total = computed(() => filtered.value.reduce((sum,r) => sum + r.quote.total.amountInclVatCents, 0));
const studentsTotal = computed(() => filtered.value.reduce((sum,r) => sum + Number(r.payload.aantalLeerlingen), 0));
const selectedId = computed(() => /^#\/aanvragen\/([1-9]\d*)$/.exec(props.route)?.[1]);
const isList = computed(() => props.route === '#/aanvragen');
const record = computed(() => records.value.find(r => r.id === Number(selectedId.value)));
const editing = ref(false), busy = ref(false), error = ref(''), message = ref('');
const students = ref<number | null>(null), supervisors = ref<number | null>(null);
const changes = reactive<Record<string,string>>({});
const title = ref<HTMLElement | null>(null), alert = ref<HTMLElement | null>(null);
let operation = 0;
let noticeTimer: ReturnType<typeof setTimeout> | undefined;
const textFields = [ ['schoolnaam','Schoolnaam'],['contactpersoonVoornaam','Voornaam contactpersoon'],['contactpersoonAchternaam','Achternaam contactpersoon'],['email','E-mailadres'],['adres','Adres'],['postcode','Postcode'],['plaats','Plaats'],['schoolTelefoonnummer','Schooltelefoon'],['contactpersoonTelefoonnummer','Telefoon contactpersoon'] ];
const cateringFields = [ ['remiseBreak','Koek en limonade'],['kazerneBreak','Chips en limonade'],['fortgrachtBreak','Fruit en limonade'],['waterijsje','Waterijsjes'],['glasLimonade','Glazen limonade'] ];
const money = (cents: number) => new Intl.NumberFormat('nl-NL',{style:'currency',currency:'EUR'}).format(cents/100);
const date = (value: string) => new Intl.DateTimeFormat('nl-NL',{day:'numeric',month:'short',year:'numeric',timeZone:'Europe/Amsterdam'}).format(new Date(value+'T12:00:00Z'));
const sameDay = computed(() => records.value.filter(r => r.status === 'Definitief' && r.payload.bezoekdatum === record.value?.payload.bezoekdatum));
const dayStudents = computed(() => sameDay.value.reduce((sum,r) => sum + Number(r.payload.aantalLeerlingen), 0));
watch(() => [filters.search, filters.status, filters.program, filters.dateFrom, filters.dateTo, filters.sort], () => { filters.page = 1; });
watch(pages, value => { filters.page = Math.min(filters.page, value); });
watch(() => props.route, async () => {
  operation++; editing.value=false; busy.value=false; error.value=''; message.value=''; clearTimeout(noticeTimer);
  await nextTick(); title.value?.focus(); window.scrollTo({top:0,behavior:'instant'});
}, { immediate: true });
onBeforeUnmount(() => { operation++; clearTimeout(noticeTimer); });
function edit(): void {
  if (!record.value) return;
  Object.keys(changes).forEach(key => delete changes[key]);
  for (const key of [...textFields.map(([key])=>key), ...cateringFields.map(([key])=>key), 'land','lunchChoice','remiseLunch','opmerkingen']) changes[key] = record.value.payload[key];
  students.value = Number(record.value.payload.aantalLeerlingen); supervisors.value = Number(record.value.payload.aantalBegeleiders);
  editing.value=true; error.value=''; message.value='';
  void nextTick(() => document.getElementById('edit-schoolnaam')?.focus());
}
async function save(action: 'edit' | 'status', target = ''): Promise<void> {
  if (!record.value || busy.value) return;
  const current = ++operation;
  busy.value=true; error.value=''; message.value='';
  try {
    await changeRecord(record.value, action, action === 'edit' ? { ...changes, aantalLeerlingen:String(students.value ?? ''), aantalBegeleiders:String(supervisors.value ?? ''), remiseLunch: changes.lunchChoice === 'eigen_picknick' ? '0' : changes.remiseLunch } : { target });
    if (current !== operation) return;
    editing.value=false;
    message.value=action === 'edit' ? 'De demoaanvraag en voorbeeldprijs zijn bijgewerkt.' : 'De demostatus is gewijzigd. Er is geen e-mail verstuurd.';
    clearTimeout(noticeTimer); noticeTimer=setTimeout(() => { message.value=''; }, 7000);
    await nextTick(); title.value?.focus();
  } catch (e) {
    if (current !== operation) return;
    error.value=e instanceof Error ? e.message : 'Probeer opnieuw.';
    await nextTick(); alert.value?.focus();
  } finally { if(current === operation) busy.value=false; }
}
</script>

<template>
  <main class="dashboard">
    <template v-if="isList">
      <div class="dashboard-heading"><div><p class="dashboard-kicker">Aanvragenbeheer</p><h2 ref="title" tabindex="-1">Van aanvraag naar overzicht.</h2><p>Open een school, wijzig de gegevens en probeer een statuswijziging.</p></div><AdminButton @click="$emit('new-form')"><span class="dashboard-inline">Nieuwe demoaanvraag <ArrowUpRight :size="18" aria-hidden="true" /></span></AdminButton></div>
      <p v-if="lastCreated" class="dashboard-own">Je eigen demoaanvraag staat klaar. <a :href="`#/aanvragen/${lastCreated}`">Open aanvraag {{ lastCreated }}</a></p>
      <div class="dashboard-stats" aria-label="Totalen van de huidige selectie"><div><span>Aanvragen in selectie</span><strong data-testid="record-count">{{ filtered.length }}</strong><small>{{ records.length }} in jouw omgeving</small></div><div><span>Leerlingen in selectie</span><strong>{{ studentsTotal.toLocaleString('nl-NL') }}</strong><small>Fictieve groepsaantallen</small></div><div><span>Som van voorbeeldprijzen</span><strong data-testid="price-total">{{ money(total) }}</strong><small>Inclusief btw · geen gerealiseerde omzet</small></div></div>
      <section class="admin-card" aria-label="Aanvragen zoeken en filteren"><div class="dashboard-filters">
        <label class="dashboard-search">School zoeken <span><Search :size="18" aria-hidden="true" /><input id="dashboard-search" v-model="filters.search" maxlength="150" type="search" placeholder="Bijvoorbeeld Demoschool 001" /></span></label>
        <label>Status<select id="filter-status" v-model="filters.status"><option value="">Alle statussen</option><option v-for="status in BOOKING_STATUSES" :key="status">{{ status }}</option></select></label>
        <label>Programma<select id="filter-program" v-model="filters.program"><option value="">Alle programma’s</option><option value="dag">Dagprogramma</option><option value="ochtend">Ochtendprogramma</option></select></label>
        <label>Volgorde<select id="filter-sort" v-model="filters.sort"><option value="desc">Bezoekdatum: nieuwste eerst</option><option value="asc">Bezoekdatum: oudste eerst</option></select></label>
        <label>Bezoekdatum vanaf<input id="filter-from" v-model="filters.dateFrom" type="date" /></label><label>Bezoekdatum tot en met<input id="filter-to" v-model="filters.dateTo" type="date" /></label>
        <AdminButton variant="secondary" @click="Object.assign(filters, defaults)">Filters wissen</AdminButton>
      </div><p v-if="dateError" role="alert" class="dashboard-error">{{ dateError }}</p></section>
      <section class="admin-card dashboard-list" aria-label="Aanvragen"><div class="dashboard-list-title"><h3><ClipboardList :size="20" aria-hidden="true" /> Aanvragen</h3><span role="status">{{ filtered.length }} gevonden</span></div>
        <table v-if="pageRows.length"><thead><tr><th scope="col">School / programma</th><th scope="col">Bezoekdatum</th><th scope="col">Leerlingen</th><th scope="col">Voorbeeldprijs</th><th scope="col">Status</th></tr></thead><tbody><tr v-for="r in pageRows" :key="r.id"><td><a :href="`#/aanvragen/${r.id}`">{{ r.payload.schoolnaam }}</a><span>{{ r.programLabel }}</span></td><td data-label="Bezoekdatum">{{ date(r.payload.bezoekdatum) }}</td><td data-label="Leerlingen">{{ r.payload.aantalLeerlingen }}</td><td data-label="Voorbeeldprijs">{{ money(r.quote.total.amountInclVatCents) }}</td><td data-label="Status"><span class="dashboard-status" :class="{ 'is-confirmed': r.status === 'Definitief', 'is-rejected': r.status === 'Afgewezen' }">{{ r.status }}</span></td></tr></tbody></table>
        <p v-else class="dashboard-empty">Geen aanvragen gevonden. Pas de filters aan.</p><nav class="dashboard-pagination" aria-label="Aanvraagpagina’s"><AdminButton variant="secondary" :disabled="filters.page <= 1" @click="filters.page--">Vorige</AdminButton><span>Pagina {{ filters.page }} van {{ pages }}</span><AdminButton variant="secondary" :disabled="filters.page >= pages" @click="filters.page++">Volgende</AdminButton></nav>
      </section>
    </template>
    <template v-else-if="record">
      <a class="dashboard-back" href="#/aanvragen"><ArrowLeft :size="18" aria-hidden="true" /> Terug naar aanvragen</a><div class="dashboard-heading"><div><p class="dashboard-kicker">Aanvraag {{ record.id }} · fictieve gegevens</p><h2 ref="title" tabindex="-1">{{ record.payload.schoolnaam }}</h2><p>{{ date(record.payload.bezoekdatum) }} · {{ record.programLabel }}</p></div><span class="dashboard-status" :class="{ 'is-confirmed': record.status === 'Definitief' }">{{ record.status }}</span></div>
      <p v-if="message" class="dashboard-success" role="status">{{ message }}</p><p v-if="error" ref="alert" tabindex="-1" class="dashboard-error" role="alert">{{ error }}</p><p v-for="warning in record.warnings" :key="warning" class="dashboard-advisory">{{ warning }}. Bij definitief maken wordt dit opnieuw gecontroleerd.</p>
      <form v-if="editing" class="admin-card dashboard-edit" novalidate @submit.prevent="save('edit')">
        <h3>Demoaanvraag bewerken</h3><p>Wijzig fictieve schoolgegevens, aantallen of catering. De voorbeeldprijs wordt bij opslaan opnieuw berekend.</p>
        <fieldset :disabled="busy"><legend>School en contact</legend><div class="dashboard-edit-grid"><label v-for="[key,label] in textFields" :key="key">{{ label }}<input :id="`edit-${key}`" v-model="changes[key]" :type="key === 'email' ? 'email' : 'text'" maxlength="150" /></label><label>Land<select v-model="changes.land"><option>Nederland</option><option>België</option></select></label></div></fieldset>
        <fieldset :disabled="busy"><legend>Leerlingen en begeleiders</legend><p>In beheer zijn aantallen vanaf 1 leerling mogelijk. Nieuwe aanvragen en definitief maken hebben aanvullende regels. Afwijkingen kunnen bij een optie als aandachtspunt blijven staan.</p><div class="dashboard-edit-grid"><AdminNumberField v-model="students" name="students" label="Leerlingen" :min="1" :max="1000" /><AdminNumberField v-model="supervisors" name="supervisors" label="Begeleiders" :min="0" :max="50" /></div></fieldset>
        <fieldset :disabled="busy"><legend>Catering en opmerkingen</legend><div class="dashboard-edit-grid"><label v-for="[key,label] in cateringFields" :key="key">{{ label }}<input :id="`edit-${key}`" v-model="changes[key]" type="number" min="0" step="1" /></label><label>Lunch<select id="edit-lunch" v-model="changes.lunchChoice"><option value="eigen_picknick">Eigen picknick</option><option value="remise_lunch">Voorbeeldlunch</option></select></label><label v-if="changes.lunchChoice === 'remise_lunch'">Aantal lunches<input v-model="changes.remiseLunch" type="number" min="1" step="1" /></label></div><label>Opmerkingen<textarea id="edit-comments" v-model="changes.opmerkingen" maxlength="600" rows="3" /></label></fieldset>
        <div class="dashboard-actions"><AdminButton type="submit" :loading="busy">Wijzigingen opslaan</AdminButton><AdminButton variant="secondary" :disabled="busy" @click="editing=false; error=''">Annuleren</AdminButton></div>
      </form>
      <div v-else class="dashboard-detail-grid">
        <section class="admin-card"><h3>School en contact</h3><dl class="admin-details"><dt>School</dt><dd>{{ record.payload.schoolnaam }}</dd><dt>Adres</dt><dd>{{ record.payload.adres }}<br />{{ record.payload.postcode }} {{ record.payload.plaats }}<br />{{ record.payload.land }}</dd><dt>Contactpersoon</dt><dd>{{ record.payload.contactpersoonVoornaam }} {{ record.payload.contactpersoonAchternaam }}</dd><dt>E-mail</dt><dd>{{ record.payload.email }}</dd><dt>Telefoon</dt><dd>{{ record.payload.contactpersoonTelefoonnummer }}</dd></dl><AdminButton id="edit-booking" @click="edit">Gegevens bewerken</AdminButton></section>
        <section class="admin-card"><h3>Programma en aantallen</h3><dl class="admin-details"><dt>Onderwijs</dt><dd>{{ record.sectorLabel }}</dd><dt>Groepen</dt><dd><span v-for="label in record.educationLabels" :key="label" class="dashboard-line">{{ label }}</span></dd><dt>Programma</dt><dd>{{ record.programLabel }}</dd><dt>Keuzemodule</dt><dd>{{ record.moduleLabel }}</dd><dt>Leerlingen</dt><dd data-testid="detail-students">{{ record.payload.aantalLeerlingen }}</dd><dt>Begeleiders</dt><dd>{{ record.payload.aantalBegeleiders }}</dd></dl></section>
        <section class="admin-card"><h3>Catering en voorbeeldprijs</h3><p>{{ record.payload.lunchChoice === 'eigen_picknick' ? 'Eigen picknick' : 'Voorbeeldlunch' }}</p><ul class="dashboard-price-lines"><li v-for="line in [...record.quote.visit.lines, ...record.quote.foodAndDrink.lines]" :key="line.key"><span>{{ line.label }}</span><strong>{{ money(line.totalInclVatCents) }}</strong></li></ul><div class="dashboard-price-total"><span>Totaal incl. btw</span><strong data-testid="detail-price">{{ money(record.quote.total.amountInclVatCents) }}</strong></div><p class="demo-muted">Waarvan btw: {{ money(record.quote.total.vatAmountCents) }}. {{ record.quote.visit.freeSupervisors }} gratis begeleiders, {{ record.quote.visit.paidSupervisors }} betaald. Alleen voorbeeldtarieven.</p></section>
        <section class="admin-card"><h3>Status en beschikbaarheid</h3><p>Alleen definitieve aanvragen tellen mee voor de dagcapaciteit. Deze dag: <strong data-testid="day-capacity">{{ sameDay.length }} scholen · {{ dayStudents }} leerlingen</strong>.</p><p>Wijzig uitsluitend de demostatus. Er wordt geen echte reservering gemaakt of e-mail verstuurd.</p><div class="dashboard-actions"><AdminButton v-for="status in BOOKING_STATUSES.filter(s=>s!==record?.status)" :key="status" variant="secondary" :disabled="busy" @click="save('status', status)">{{ status === 'Definitief' ? 'Definitief maken' : status === 'In optie' ? 'Terug naar In optie' : 'Afwijzen' }}</AdminButton></div></section>
        <section class="admin-card dashboard-comments"><h3>Opmerkingen</h3><p>{{ record.payload.opmerkingen || 'Geen opmerkingen.' }}</p></section>
      </div>
    </template>
    <section v-else class="admin-card"><h2 ref="title" tabindex="-1">Deze demoaanvraag is niet meer beschikbaar.</h2><p>Na vernieuwen of opnieuw starten verdwijnen eigen aanvragen. De 100 voorbeelden staan weer klaar.</p><a href="#/aanvragen">Terug naar het overzicht</a></section>
  </main>
</template>
