<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';

const categories = [
    {
        title: 'Websites',
        text: 'Publieke websites waarin inhoud, toegankelijkheid en een heldere route voor bezoekers centraal staan.',
    },
    {
        title: 'Dashboards',
        text: 'Beheeromgevingen die gegevens en terugkerende werkzaamheden overzichtelijk maken.',
    },
    {
        title: 'Webshops',
        text: 'Verkoopomgevingen en catalogi met aandacht voor producten, beheer en gebruiksgemak.',
    },
    {
        title: 'Formulieren en boekingssystemen',
        text: 'Praktische systemen voor aanvragen en afspraken, afgestemd op het proces erachter.',
    },
];
</script>

<template>
    <Head title="Werk en projecten" />
    <PublicLayout>
        <header class="max-w-3xl">
            <p
                class="text-sm font-semibold tracking-wide text-amber-700 uppercase"
            >
                Gerealiseerd en in ontwikkeling
            </p>
            <h1 class="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl">
                Werk
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                Hier groeit een zorgvuldig samengesteld overzicht van projecten
                die ik heb gebouwd of waaraan ik werk. Zonder klantnamen of
                resultaten te publiceren zolang daar geen duidelijke toestemming
                voor is.
            </p>
        </header>

        <section aria-labelledby="projectcategorieen" class="mt-14">
            <h2 id="projectcategorieen" class="text-2xl font-semibold">
                Soorten projecten
            </h2>
            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <article
                    v-for="category in categories"
                    :key="category.title"
                    class="border-t-2 border-stone-300 pt-5"
                >
                    <h3 class="text-lg font-semibold">{{ category.title }}</h3>
                    <p class="mt-2 leading-7 text-slate-600">
                        {{ category.text }}
                    </p>
                </article>
            </div>
        </section>

        <aside class="mt-14 max-w-3xl border-l-2 border-amber-600 pl-5">
            <h2 class="text-lg font-semibold">
                Op zoek naar wat ik voor je kan bouwen?
            </h2>
            <p class="mt-2 leading-7 text-slate-600">
                Op de dienstenpagina staat het aanbod voor nieuwe projecten
                overzichtelijk bij elkaar.
            </p>
            <Link
                href="/diensten"
                class="mt-3 inline-block rounded-sm font-semibold text-amber-800 underline decoration-amber-500 underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-700"
            >
                Bekijk de diensten
            </Link>
        </aside>
    </PublicLayout>
</template>
