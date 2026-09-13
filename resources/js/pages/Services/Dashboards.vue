<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicButtonLink from '@/components/public/PublicButtonLink.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Dashboards en beheeromgevingen" />
    <PublicLayout>
        <article class="max-w-3xl">
            <span
                aria-hidden="true"
                class="block h-1 w-10 rounded-full bg-amber-600"
            />
            <h1
                class="mt-5 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"
            >
                Overzicht in je aanvragen en werkzaamheden
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                Staan aanvragen, taken of afspraken verspreid over bestanden en
                losse berichten? Ik help je onderzoeken welk overzicht
                ontbreekt. Een gerichte webapplicatie kan gegevens samenbrengen
                en duidelijk maken wat er nog moet gebeuren.
            </p>
            <section
                aria-labelledby="werkwijze"
                class="mt-10 border-l-2 border-amber-600 pl-5"
            >
                <h2 id="werkwijze" class="text-lg font-semibold">
                    Beginnen bij je werkproces
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Bijvoorbeeld een aanvragenoverzicht waarin je zoekt op naam,
                    filtert op datum en een status bijhoudt. We bespreken wie
                    ermee werkt, welke gegevens nodig zijn en welke handelingen
                    en koppelingen binnen de opdracht passen. Zo heeft elk
                    onderdeel een duidelijke taak.
                </p>
            </section>
            <PublicButtonLink href="/contact" class="mt-8">
                Bespreek je werkzaamheden
            </PublicButtonLink>
        </article>
    </PublicLayout>
</template>
