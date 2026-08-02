<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';

const specialties = [
    'Websites die een verhaal helder overbrengen',
    'Dashboards en beheeromgevingen die overzicht geven',
    'Webshops en productcatalogi die prettig navigeren',
    'Formulieren en boekingssystemen die handwerk verminderen',
];
</script>

<template>
    <Head title="Websites en webapplicaties op maat" />
    <PublicLayout>
        <section aria-labelledby="introductie" class="max-w-4xl">
            <p
                class="text-sm font-semibold tracking-wide text-amber-700 uppercase"
            >
                Persoonlijk webdevelopment
            </p>
            <h1
                id="introductie"
                class="mt-3 max-w-3xl text-4xl font-semibold tracking-tight sm:text-5xl"
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
                <Link
                    href="/werk"
                    class="rounded-md bg-slate-900 px-5 py-3 font-semibold text-white hover:bg-slate-700 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-700"
                >
                    Bekijk mijn werk
                </Link>
                <Link
                    href="/diensten"
                    class="rounded-md border border-stone-300 px-5 py-3 font-semibold text-slate-800 hover:border-amber-600 hover:text-amber-900 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-700"
                >
                    Bekijk de diensten
                </Link>
            </div>
        </section>

        <section
            aria-labelledby="bouwstenen"
            class="mt-16 border-t border-stone-200 pt-10"
        >
            <div class="grid gap-8 lg:grid-cols-[1fr_2fr]">
                <div>
                    <p class="text-sm font-semibold text-amber-700">
                        Van idee naar bruikbaar
                    </p>
                    <h2
                        id="bouwstenen"
                        class="mt-2 text-2xl font-semibold tracking-tight"
                    >
                        Wat ik bouw
                    </h2>
                </div>
                <ul class="grid gap-4 sm:grid-cols-2">
                    <li
                        v-for="specialty in specialties"
                        :key="specialty"
                        class="border-l-2 border-amber-600 pl-4 leading-7 text-slate-700"
                    >
                        {{ specialty }}
                    </li>
                </ul>
            </div>
        </section>

        <section class="mt-16 max-w-3xl rounded-lg bg-stone-100 p-7 sm:p-9">
            <p class="text-sm font-semibold text-amber-700">
                Een idee of lastig proces?
            </p>
            <h2 class="mt-2 text-2xl font-semibold">
                Laten we het samen helder maken.
            </h2>
            <p class="mt-3 leading-7 text-slate-600">
                Vertel waar je tegenaan loopt. Dan kijken we eerst naar de
                mensen en het werkproces, en pas daarna naar de techniek.
            </p>
            <Link
                href="/contact"
                class="mt-5 inline-block rounded-sm font-semibold text-amber-800 underline decoration-amber-500 underline-offset-4 hover:text-amber-950 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-700"
            >
                Neem contact op
            </Link>
        </section>
    </PublicLayout>
</template>
