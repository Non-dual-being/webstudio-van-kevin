<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
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
                Dashboards en beheeromgevingen
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                Ik ontwikkel rustige beheeromgevingen die informatie, gebruikers
                en dagelijkse taken op één begrijpelijke plek samenbrengen. De
                werkwijze van jouw team is daarbij het vertrekpunt.
            </p>
            <section
                aria-labelledby="werkwijze"
                class="mt-10 border-l-2 border-amber-600 pl-5"
            >
                <h2 id="werkwijze" class="text-lg font-semibold">
                    Overzicht zonder omwegen
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Samen brengen we terugkerende handelingen en uitzonderingen
                    in kaart. Zo ondersteunt het systeem het werk, in plaats van
                    andersom.
                </p>
            </section>
        </article>
    </PublicLayout>
</template>
