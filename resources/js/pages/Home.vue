<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ProjectCard from '@/components/public/ProjectCard.vue';
import PublicButtonLink from '@/components/public/PublicButtonLink.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { PortfolioProject } from '@/types/portfolio';

defineProps<{ featuredProject: PortfolioProject }>();

const specialties = [
    {
        title: 'Websites',
        text: 'Toegankelijke websites die je verhaal helder overbrengen en prettig werken op ieder scherm.',
    },
    {
        title: 'Dashboards en beheeromgevingen',
        text: 'Rustige systemen die informatie en dagelijkse taken overzichtelijk bij elkaar brengen.',
    },
    {
        title: 'Webshops en productcatalogi',
        text: 'Duidelijke verkoopomgevingen waarin klanten en beheerders eenvoudig hun weg vinden.',
    },
    {
        title: 'Formulieren en boekingssystemen',
        text: 'Praktisch maatwerk dat aanvragen, afspraken en terugkerend handwerk eenvoudiger maakt.',
    },
];
</script>

<template>
    <Head title="Websites en webapplicaties op maat" />
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
                Ik bouw begrijpelijke digitale oplossingen die echt bij je werk
                passen
            </h1>
            <div
                class="mt-7 max-w-3xl space-y-4 text-lg leading-8 text-slate-700"
            >
                <p>
                    Ik ben Kevin: een technisch nieuwsgierige webdeveloper die
                    graag uitzoekt hoe iets eenvoudiger, duidelijker en
                    prettiger kan werken.
                </p>
                <p>
                    Samen vertalen we jouw dagelijkse proces naar een website,
                    webshop, dashboard of praktisch maatwerksysteem. Ik houd de
                    techniek begrijpelijk, zodat keuzes niet alleen goed gebouwd
                    maar ook goed te volgen zijn.
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
                Van een eerste idee naar een bruikbare oplossing die aansluit op
                de mensen en het werk erachter.
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

        <section class="mt-16 max-w-3xl rounded-xl bg-stone-100 p-7 sm:p-9">
            <p
                class="flex items-center gap-2 text-sm font-semibold text-slate-600"
            >
                <span
                    aria-hidden="true"
                    class="size-2 rounded-full bg-amber-600"
                />
                Een idee of lastig proces?
            </p>
            <h2 class="mt-3 text-2xl font-semibold text-slate-950">
                Laten we het samen helder maken.
            </h2>
            <p class="mt-3 leading-7 text-slate-600">
                Vertel waar je tegenaan loopt. Dan kijken we eerst naar de
                mensen en het werkproces, en pas daarna naar de techniek.
            </p>
            <PublicButtonLink href="/contact" class="mt-6">
                Neem contact op
            </PublicButtonLink>
        </section>
    </PublicLayout>
</template>
