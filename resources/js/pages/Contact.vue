<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';

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
        <section
            aria-labelledby="contact-title"
            class="grid items-start gap-10 lg:grid-cols-[minmax(0,1.15fr)_minmax(20rem,0.85fr)] lg:gap-14"
        >
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
                <div class="mt-6">
                    <p class="leading-7 text-slate-600">
                        Je kunt me mailen op:
                    </p>
                    <a
                        href="mailto:info@kevinwebstudio.nl"
                        class="mt-1 inline-flex min-h-11 items-center rounded-sm text-lg font-semibold text-slate-900 underline decoration-stone-300 underline-offset-4 transition-colors duration-150 ease-out hover:decoration-slate-900 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-600 motion-reduce:transition-none"
                    >
                        info@kevinwebstudio.nl
                    </a>
                </div>
            </div>

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
            </aside>
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
