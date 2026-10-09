<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import { computed, nextTick, ref } from 'vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { privacy } from '@/routes';
import { store } from '@/routes/contact';

const props = defineProps<{
    attempt: string;
    services: { value: string; label: string }[];
    received: boolean;
}>();
const form = useForm({
    name: '',
    email: '',
    service: '',
    body: '',
    website: '',
    attempt: props.attempt,
});
const received = ref(props.received);
const transportError = ref('');
const generalError = computed(
    () =>
        (form.errors as Record<string, string>).form ||
        form.errors.attempt ||
        form.errors.website ||
        transportError.value,
);
const fieldClass =
    'mt-2 block min-h-12 w-full rounded-md border border-stone-300 bg-white px-3.5 py-3 text-base text-slate-950 shadow-xs focus:border-blue-500 focus:ring-4 focus:ring-blue-500/15 focus:outline-hidden disabled:opacity-70 aria-invalid:border-red-700 aria-invalid:focus:border-red-700 aria-invalid:focus:ring-red-700/10';

async function focusError() {
    await nextTick();
    const field = ['name', 'email', 'service', 'body'].find(
        (key) => (form.errors as Record<string, string>)[key],
    );
    document
        .getElementById(field ? `contact-${field}` : 'contact-error')
        ?.focus();
}

function connectionFailed() {
    transportError.value =
        'Verzenden kon niet worden bevestigd. Je tekst blijft staan. Controleer je verbinding en probeer opnieuw; dezelfde aanvraag wordt niet dubbel opgeslagen.';
    void focusError();

    return false;
}

function submit() {
    if (form.processing) {
        return;
    }

    received.value = false;
    transportError.value = '';
    form.submit(store(), {
        preserveScroll: true,
        onError: () => {
            // The GET after a rejected/expired attempt supplies a fresh session-bound key.
            form.attempt = props.attempt;
            void focusError();
        },
        onSuccess: async () => {
            received.value = props.received;

            if (received.value) {
                form.reset();
                form.attempt = props.attempt;
                await nextTick();
                document.getElementById('contact-success')?.focus();
            }
        },
        onHttpException: connectionFailed,
        onNetworkError: connectionFailed,
    });
}

const conversationPoints = [
    {
        title: 'Doel',
        text: 'Wat wil je met je website, formulier of webapplicatie bereiken?',
    },
    {
        title: 'Gebruikers',
        text: 'Voor wie is het bedoeld en wat moeten die mensen kunnen doen?',
    },
    {
        title: 'Huidige knelpunten',
        text: 'Wat gaat er nu lastig, bijvoorbeeld bij het vinden van informatie of het verwerken van aanvragen?',
    },
    {
        title: 'Planning',
        text: 'Is er een gewenste start, deadline of moment om rekening mee te houden?',
    },
];

const processSteps = [
    {
        title: 'Kennismaken',
        text: 'Ik luister naar je idee en vraag door over de mensen en het werk erachter.',
    },
    {
        title: 'Voorstel afstemmen',
        text: 'We spreken af wat ik bouw, welke input nodig is en welke keuzes nog openstaan.',
    },
    {
        title: 'Bouwen en bijstellen',
        text: 'Je bekijkt een eerste versie en geeft feedback. Bij oplevering maken we duidelijke afspraken over beheer en overdracht.',
    },
];
</script>

<template>
    <Head title="Contact opnemen" />
    <PublicLayout>
        <section aria-labelledby="contact-title">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold tracking-wide text-slate-600">
                    Contact
                </p>
                <h1
                    id="contact-title"
                    class="mt-3 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"
                >
                    Vertel me wat je wilt maken.
                </h1>
                <div class="mt-6 space-y-4 text-lg leading-8 text-slate-700">
                    <p>
                        Heb je een idee voor een website, een formulier of een
                        webapplicatie? Vertel me wat je wilt bereiken, voor wie
                        het bedoeld is en wat er nu lastig gaat.
                    </p>
                    <p>
                        Je hoeft de technische oplossing nog niet te kennen. Ik
                        denk met je mee over wat nodig is en welke eerste stap
                        past.
                    </p>
                </div>
            </div>

            <div
                class="mt-10 grid items-start gap-10 lg:grid-cols-[minmax(0,1.15fr)_minmax(20rem,0.85fr)] lg:gap-14"
            >
                <form
                    :action="store.url()"
                    method="post"
                    novalidate
                    :aria-busy="form.processing"
                    class="space-y-6"
                    @submit.prevent="submit"
                >
                    <div
                        v-if="received"
                        id="contact-success"
                        role="status"
                        tabindex="-1"
                        class="flex items-start gap-3 rounded-md border border-stone-300 bg-stone-100 p-5 leading-7 text-slate-900 focus:outline-2 focus:outline-offset-2 focus:outline-slate-500"
                    >
                        <Check
                            aria-hidden="true"
                            class="mt-1 size-5 shrink-0 text-slate-600"
                        />
                        <p>
                            Je bericht is ontvangen. Ik neem contact met je op.
                        </p>
                    </div>
                    <div
                        v-if="generalError"
                        id="contact-error"
                        role="alert"
                        tabindex="-1"
                        class="rounded-md border border-red-700/25 bg-red-50 p-4 leading-7 text-red-900 focus:outline-2 focus:outline-offset-2 focus:outline-red-700"
                    >
                        {{ generalError }}
                    </div>
                    <div>
                        <label
                            for="contact-name"
                            class="font-semibold text-slate-900"
                            >Naam</label
                        >
                        <input
                            id="contact-name"
                            v-model="form.name"
                            name="name"
                            autocomplete="name"
                            required
                            minlength="2"
                            maxlength="120"
                            :readonly="form.processing"
                            :aria-invalid="!!form.errors.name"
                            :aria-describedby="
                                form.errors.name
                                    ? 'contact-name-error'
                                    : undefined
                            "
                            :class="fieldClass"
                        />
                        <p
                            v-if="form.errors.name"
                            id="contact-name-error"
                            class="mt-2 text-sm leading-6 text-red-800"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="contact-email"
                            class="font-semibold text-slate-900"
                            >E-mailadres</label
                        >
                        <input
                            id="contact-email"
                            v-model="form.email"
                            name="email"
                            type="email"
                            inputmode="email"
                            autocomplete="email"
                            required
                            maxlength="254"
                            :readonly="form.processing"
                            :aria-invalid="!!form.errors.email"
                            :aria-describedby="
                                form.errors.email
                                    ? 'contact-email-help contact-email-error'
                                    : 'contact-email-help'
                            "
                            :class="fieldClass"
                        />
                        <p
                            id="contact-email-help"
                            class="mt-2 text-sm leading-6 text-slate-600"
                        >
                            Op dit adres ontvang je een bevestiging.
                        </p>
                        <p
                            v-if="form.errors.email"
                            id="contact-email-error"
                            class="mt-2 text-sm leading-6 text-red-800"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="contact-service"
                            class="font-semibold text-slate-900"
                            >Waar kan ik je bij helpen?
                            <span class="font-normal text-slate-600"
                                >(optioneel)</span
                            ></label
                        >
                        <select
                            id="contact-service"
                            v-model="form.service"
                            name="service"
                            :disabled="form.processing"
                            :aria-invalid="!!form.errors.service"
                            :aria-describedby="
                                form.errors.service
                                    ? 'contact-service-error'
                                    : undefined
                            "
                            :class="fieldClass"
                        >
                            <option value="">Ik weet het nog niet</option>
                            <option
                                v-for="service in services"
                                :key="service.value"
                                :value="service.value"
                            >
                                {{ service.label }}
                            </option>
                        </select>
                        <p
                            v-if="form.errors.service"
                            id="contact-service-error"
                            class="mt-2 text-sm leading-6 text-red-800"
                        >
                            {{ form.errors.service }}
                        </p>
                    </div>
                    <div>
                        <label
                            for="contact-body"
                            class="font-semibold text-slate-900"
                            >Je bericht</label
                        >
                        <p
                            id="contact-body-help"
                            class="mt-2 text-sm leading-6 text-slate-600"
                        >
                            Wat is je idee, voor wie is het bedoeld en wat wil
                            je bereiken? Gebruik 20 tot 5000 tekens.
                        </p>
                        <textarea
                            id="contact-body"
                            v-model="form.body"
                            name="body"
                            rows="6"
                            required
                            minlength="20"
                            maxlength="5000"
                            :readonly="form.processing"
                            :aria-invalid="!!form.errors.body"
                            :aria-describedby="
                                form.errors.body
                                    ? 'contact-body-help contact-body-error'
                                    : 'contact-body-help'
                            "
                            :class="[
                                fieldClass,
                                'h-48 resize-none overflow-y-auto',
                            ]"
                        />
                        <p
                            v-if="form.errors.body"
                            id="contact-body-error"
                            class="mt-2 text-sm leading-6 text-red-800"
                        >
                            {{ form.errors.body }}
                        </p>
                    </div>
                    <div hidden aria-hidden="true">
                        <label for="contact-website">Website</label>
                        <input
                            id="contact-website"
                            v-model="form.website"
                            name="website"
                            tabindex="-1"
                            autocomplete="off"
                        />
                    </div>
                    <input type="hidden" name="attempt" :value="form.attempt" />
                    <div class="space-y-4">
                        <p class="text-sm leading-6 text-slate-600">
                            Ik gebruik je gegevens om op je aanvraag te
                            reageren. Lees meer in de
                            <Link
                                :href="privacy()"
                                class="rounded-sm text-slate-900 underline decoration-stone-400 underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-600"
                                >privacyverklaring</Link
                            >.
                        </p>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="contact-submit inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-md border border-slate-900 bg-slate-900 px-5 py-3 font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-600 disabled:cursor-wait disabled:opacity-70 sm:w-auto"
                        >
                            <span
                                v-if="form.processing"
                                aria-hidden="true"
                                class="size-4 animate-spin rounded-full border-2 border-white/40 border-t-white motion-reduce:animate-none"
                            />
                            <span aria-live="polite">{{
                                form.processing
                                    ? 'Je bericht wordt opgeslagen…'
                                    : 'Verstuur je bericht'
                            }}</span>
                        </button>
                    </div>
                </form>

                <aside
                    aria-labelledby="gesprek-titel"
                    class="rounded-xl border border-stone-200 bg-stone-100 p-6 sm:p-7"
                >
                    <h2
                        id="gesprek-titel"
                        class="text-xl font-semibold tracking-tight text-slate-950"
                    >
                        Dit helpt bij een eerste gesprek
                    </h2>
                    <dl class="mt-6 space-y-5">
                        <div
                            v-for="point in conversationPoints"
                            :key="point.title"
                            class="border-t border-stone-300 pt-5 first:border-t-0 first:pt-0"
                        >
                            <dt class="font-semibold text-slate-900">
                                {{ point.title }}
                            </dt>
                            <dd class="mt-1 leading-7 text-slate-600">
                                {{ point.text }}
                            </dd>
                        </div>
                    </dl>
                    <div class="mt-6 border-t border-stone-300 pt-5">
                        <p class="leading-7 text-slate-600">
                            Liever rechtstreeks mailen?
                        </p>
                        <a
                            href="mailto:info@kevinwebstudio.nl"
                            class="mt-1 inline-flex min-h-11 items-center rounded-sm font-semibold text-slate-900 underline decoration-stone-400 underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-600"
                            >info@kevinwebstudio.nl</a
                        >
                    </div>
                </aside>
            </div>
        </section>

        <section
            aria-labelledby="werkwijze-titel"
            class="mt-14 border-t border-stone-200 pt-10 sm:mt-16"
        >
            <h2
                id="werkwijze-titel"
                class="text-2xl font-semibold tracking-tight text-slate-950"
            >
                Van eerste gesprek naar een werkende oplossing
            </h2>
            <ol class="mt-7 grid gap-7 md:grid-cols-3 md:gap-8">
                <li
                    v-for="(step, index) in processSteps"
                    :key="step.title"
                    class="grid grid-cols-[2rem_1fr] gap-x-3"
                >
                    <span
                        aria-hidden="true"
                        class="flex size-8 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white"
                    >
                        {{ index + 1 }}
                    </span>
                    <div>
                        <h3 class="font-semibold text-slate-950">
                            {{ step.title }}
                        </h3>
                        <p class="mt-2 leading-7 text-slate-600">
                            {{ step.text }}
                        </p>
                    </div>
                </li>
            </ol>
        </section>
    </PublicLayout>
</template>

<style scoped>
.contact-submit {
    transition: background-color 160ms ease-out;
}
@media (hover: hover) and (pointer: fine) {
    .contact-submit:hover:not(:disabled) {
        background-color: var(--color-slate-800);
    }
}
.contact-submit:active:not(:disabled) {
    background-color: var(--color-slate-800);
}
@media (prefers-reduced-motion: reduce) {
    .contact-submit {
        transition: none;
    }
}
</style>
