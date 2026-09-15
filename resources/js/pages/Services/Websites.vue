<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicButtonLink from '@/components/public/PublicButtonLink.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Maatwerk websites" />
    <PublicLayout>
        <article class="max-w-3xl">
            <span
                aria-hidden="true"
                class="block h-1 w-10 rounded-full bg-amber-600"
            />
            <h1
                class="mt-5 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"
            >
                Een website die laat zien wat je doet
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                Moeten bezoekers lang zoeken naar wat je aanbiedt of hoe ze je
                bereiken? Ik kan een website bouwen met duidelijke informatie
                over je diensten, voorbeelden van je werk en een logische stap
                naar contact.
            </p>
            <section
                aria-labelledby="werkwijze"
                class="mt-10 border-l-2 border-amber-600 pl-5"
            >
                <h2 id="werkwijze" class="text-lg font-semibold">
                    Inhoud, navigatie en beheer
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Denk aan een dienstenoverzicht, een portfolio of een pagina
                    die veelgestelde vragen beantwoordt. We bepalen wat
                    bezoekers eerst moeten weten en welke inhoud je zelf wilt
                    bijhouden. Daarna spreken we de pagina’s en functies af, met
                    aandacht voor leesbaarheid en bediening op mobiel en
                    desktop.
                </p>
            </section>
            <PublicButtonLink href="/contact" class="mt-8">
                Bespreek je website
            </PublicButtonLink>
        </article>
    </PublicLayout>
</template>
