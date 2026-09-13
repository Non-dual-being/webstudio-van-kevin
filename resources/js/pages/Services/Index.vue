<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicCardAction from '@/components/public/PublicCardAction.vue';
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
            <span
                aria-hidden="true"
                class="block h-1 w-10 rounded-full bg-amber-600"
            />
            <h1
                class="mt-5 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"
            >
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
            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <article
                    v-for="service in services"
                    :key="service.title"
                    class="flex h-full flex-col rounded-xl border border-stone-200 bg-white p-6 transition-[border-color,box-shadow] duration-150 ease-out hover:border-slate-300 hover:shadow-sm motion-reduce:transition-none sm:p-7"
                >
                    <span
                        aria-hidden="true"
                        class="block h-1 w-8 rounded-full bg-amber-600"
                    />
                    <h3 class="mt-5 text-lg font-semibold text-slate-950">
                        {{ service.title }}
                    </h3>
                    <p class="mt-3 mb-6 leading-7 text-slate-600">
                        {{ service.text }}
                    </p>
                    <footer class="mt-auto border-t border-stone-200 pt-5">
                        <PublicCardAction :href="service.href">
                            {{ service.linkLabel }}
                        </PublicCardAction>
                    </footer>
                </article>
            </div>
        </section>
    </PublicLayout>
</template>
