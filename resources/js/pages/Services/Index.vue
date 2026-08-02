<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';

const services = [
    {
        title: 'Websites',
        text: 'Een toegankelijke maatwerkwebsite die jouw verhaal helder vertelt en prettig werkt op ieder scherm.',
        href: '/diensten/websites',
        linkLabel: 'Bekijk websites',
    },
    {
        title: 'Dashboards en beheeromgevingen',
        text: 'Een rustige beheerplek die gegevens en terugkerende taken begrijpelijk bij elkaar brengt.',
        href: '/diensten/dashboards',
        linkLabel: 'Bekijk dashboards',
    },
    {
        title: 'Webshops en productcatalogi',
        text: 'Een overzichtelijke verkoopomgeving die past bij je assortiment en manier van werken.',
        href: '/diensten/webshops',
        linkLabel: 'Bekijk webshops',
    },
    {
        title: 'Formulieren en boekingssystemen',
        text: 'Praktisch maatwerk voor aanvragen, afspraken en andere processen die nu onnodig veel handwerk kosten.',
        href: '/contact',
        linkLabel: 'Bespreek je proces',
    },
];
</script>

<template>
    <Head title="Diensten" />
    <PublicLayout>
        <header class="max-w-3xl">
            <p
                class="text-sm font-semibold tracking-wide text-amber-700 uppercase"
            >
                Wat ik bouw
            </p>
            <h1 class="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl">
                Digitale oplossingen die passen bij het echte werk
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                Geen vast pakket, maar een doordachte basis rond jouw bezoekers,
                informatie en dagelijkse werkwijze. Ik denk mee over wat nodig
                is en leg technische keuzes begrijpelijk uit.
            </p>
        </header>

        <section aria-labelledby="dienstenaanbod" class="mt-14">
            <h2 id="dienstenaanbod" class="text-2xl font-semibold">
                Waarmee ik kan helpen
            </h2>
            <div
                class="mt-6 grid gap-px overflow-hidden rounded-lg border border-stone-200 bg-stone-200 sm:grid-cols-2"
            >
                <article
                    v-for="service in services"
                    :key="service.title"
                    class="bg-stone-50 p-6 sm:p-7"
                >
                    <h3 class="text-lg font-semibold">{{ service.title }}</h3>
                    <p class="mt-3 leading-7 text-slate-600">
                        {{ service.text }}
                    </p>
                    <Link
                        :href="service.href"
                        class="mt-5 inline-block rounded-sm font-semibold text-amber-800 underline decoration-amber-500 underline-offset-4 hover:text-amber-950 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-700"
                    >
                        {{ service.linkLabel }}
                    </Link>
                </article>
            </div>
        </section>
    </PublicLayout>
</template>
