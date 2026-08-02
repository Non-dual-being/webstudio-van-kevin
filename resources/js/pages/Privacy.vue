<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Privacy" />
    <PublicLayout>
        <article class="max-w-3xl">
            <p
                class="text-sm font-semibold tracking-wide text-amber-700 uppercase"
            >
                Zorgvuldig met gegevens
            </p>
            <h1 class="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl">
                Privacy
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                Kevin Webstudio vindt begrijpelijke techniek en zorgvuldige
                omgang met gegevens belangrijk. De volledige privacyverklaring
                wordt toegevoegd zodra functies die persoonsgegevens verwerken
                onderdeel zijn van deze publieke website.
            </p>
            <section
                aria-labelledby="huidigesituatie"
                class="mt-10 border-l-2 border-amber-600 pl-5"
            >
                <h2 id="huidigesituatie" class="text-lg font-semibold">
                    Huidige situatie
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Deze eerste versie bevat nog geen contactformulier,
                    boekingsfunctie of andere publieke invoer van
                    persoonsgegevens.
                </p>
            </section>
        </article>
    </PublicLayout>
</template>
