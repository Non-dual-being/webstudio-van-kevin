<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ProjectCard from '@/components/public/ProjectCard.vue';
import PublicButtonLink from '@/components/public/PublicButtonLink.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { PortfolioProject } from '@/types/portfolio';

defineProps<{ featuredProject: PortfolioProject }>();

const specialties = [
    {
        title: 'Een heldere website',
        text: 'Bezoekers begrijpen niet goed wat je aanbiedt? Ik breng je diensten, werk en contactgegevens samen op een website met een duidelijke route.',
    },
    {
        title: 'Aanvragen stap voor stap',
        text: 'Komt een aanvraag binnen met ontbrekende informatie? Ik kan een formulier maken dat passende vervolgvragen stelt en invoer controleert.',
    },
    {
        title: 'Overzicht in je werkzaamheden',
        text: 'Houd je aanvragen of taken bij in losse bestanden? We onderzoeken welk overzicht je nodig hebt en wat een beheeromgeving kan ondersteunen.',
    },
    {
        title: 'Je producten in beeld',
        text: 'Wil je producten online laten zien of verkopen? We bepalen hoe je assortiment wordt ingedeeld en wat nodig is voor aanvragen of bestellen.',
    },
];

const processSteps = [
    {
        title: 'Kennismaken',
        text: 'Je vertelt wat je wilt bereiken, voor wie en wat er nu lastig gaat.',
    },
    {
        title: 'Afspreken wat we bouwen',
        text: 'We spreken de onderdelen af en welke inhoud en gegevens ik van je nodig heb.',
    },
    {
        title: 'Een eerste versie bekijken',
        text: 'Ik laat zien hoe het werkt. Met jouw feedback stel ik de afgesproken onderdelen bij.',
    },
    {
        title: 'Opleveren met duidelijke afspraken',
        text: 'We lopen de belangrijkste handelingen na en spreken af hoe beheer, overdracht en eventuele nazorg zijn geregeld.',
    },
];
</script>

<template>
    <Head title="Websites en webapplicaties voor jouw werk" />
    <PublicLayout>
        <section aria-labelledby="introductie" class="max-w-4xl">
            <span
                aria-hidden="true"
                class="block h-1 w-10 rounded-full bg-amber-600"
            />
            <h1
                id="introductie"
                class="mt-5 max-w-3xl text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"
            >
                Websites en webapplicaties die passen bij jouw werk.
            </h1>
            <div
                class="mt-7 max-w-3xl space-y-4 text-lg leading-8 text-slate-700"
            >
                <p>
                    Ik ben Kevin. Ik bouw websites, formulieren en
                    webapplicaties die aansluiten op de mensen die ze gebruiken.
                    Samen kijken we wat jouw bezoekers nodig hebben en waar je
                    werk eenvoudiger kan. Dat vertaal ik naar een heldere
                    website of een praktisch systeem.
                </p>
            </div>
            <div class="mt-8 flex flex-wrap gap-3">
                <PublicButtonLink href="/werk">
                    Bekijk mijn werk
                </PublicButtonLink>
                <PublicButtonLink href="/diensten" variant="secondary">
                    Bekijk de diensten
                </PublicButtonLink>
            </div>
        </section>

        <section
            aria-labelledby="bouwstenen"
            class="mt-16 border-t border-stone-200 pt-10"
        >
            <h2
                id="bouwstenen"
                class="text-2xl font-semibold tracking-tight text-slate-950"
            >
                Wat ik bouw
            </h2>
            <p class="mt-3 max-w-2xl leading-7 text-slate-600">
                Van informatie op je website tot de afhandeling van een
                aanvraag: we beginnen bij wat je bezoeker of jijzelf wilt kunnen
                doen.
            </p>
            <ul class="mt-7 grid gap-5 sm:grid-cols-2">
                <li
                    v-for="specialty in specialties"
                    :key="specialty.title"
                    class="rounded-xl border border-stone-200 bg-white p-6 transition-[border-color,box-shadow] duration-150 ease-out hover:border-slate-300 hover:shadow-sm motion-reduce:transition-none"
                >
                    <span
                        aria-hidden="true"
                        class="block h-1 w-8 rounded-full bg-amber-600"
                    />
                    <h3 class="mt-5 text-lg font-semibold text-slate-950">
                        {{ specialty.title }}
                    </h3>
                    <p class="mt-2 leading-7 text-slate-600">
                        {{ specialty.text }}
                    </p>
                </li>
            </ul>
        </section>

        <section aria-labelledby="uitgelicht-project" class="mt-16">
            <h2
                id="uitgelicht-project"
                class="text-2xl font-semibold tracking-tight text-slate-950"
            >
                Uitgelicht werk
            </h2>
            <ProjectCard :project="featuredProject" class="mt-6 max-w-3xl" />
        </section>

        <section
            aria-labelledby="werkwijze"
            class="mt-16 border-t border-stone-200 pt-10"
        >
            <h2
                id="werkwijze"
                class="text-2xl font-semibold tracking-tight text-slate-950"
            >
                Zo werken we samen
            </h2>
            <ol class="mt-7 grid gap-7 sm:grid-cols-2 md:gap-8">
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

        <section class="mt-16 max-w-3xl rounded-xl bg-stone-100 p-7 sm:p-9">
            <p
                class="flex items-center gap-2 text-sm font-semibold text-slate-600"
            >
                <span
                    aria-hidden="true"
                    class="size-2 rounded-full bg-amber-600"
                />
                Een idee voor jouw website of werk?
            </p>
            <h2 class="mt-3 text-2xl font-semibold text-slate-950">
                Vertel me waar je mee aan de slag wilt.
            </h2>
            <p class="mt-3 leading-7 text-slate-600">
                Vertel wat je wilt bereiken en voor wie. Je hoeft nog niet te
                weten welke techniek daarbij past; ik denk met je mee.
            </p>
            <PublicButtonLink href="/contact" class="mt-6">
                Neem contact op
            </PublicButtonLink>
        </section>
    </PublicLayout>
</template>
