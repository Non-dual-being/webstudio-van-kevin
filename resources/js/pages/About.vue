<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicButtonLink from '@/components/public/PublicButtonLink.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Over mij" />
    <PublicLayout>
        <article class="max-w-3xl">
            <span
                aria-hidden="true"
                class="block h-1 w-10 rounded-full bg-amber-600"
            />
            <h1
                class="mt-5 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"
            >
                Over mij
            </h1>
            <div class="mt-6 space-y-4 text-lg leading-8 text-slate-700">
                <p>
                    Ik ben Kevin. Ik bouw websites en webapplicaties en zoek
                    graag uit hoe je veel informatie, regels en mogelijkheden
                    overzichtelijk kunt aanbieden. Een bezoeker moet weten waar
                    die is, welke keuze nodig is en wat de volgende stap is.
                </p>
                <p>
                    Daarom begin ik bij de mensen die de oplossing gaan
                    gebruiken. Wat willen ze doen, welke informatie hebben ze
                    nodig en waar ontstaat twijfel? Vanuit die vragen breng ik
                    structuur aan, zodat ook een uitgebreid proces een logische
                    route krijgt.
                </p>
            </div>
            <section
                aria-labelledby="aandacht"
                class="mt-10 border-l-2 border-amber-600 pl-5"
            >
                <h2 id="aandacht" class="text-lg font-semibold">
                    Samen kijken, zorgvuldig afwerken
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Tussentijds laat ik je een werkende versie zien en leg ik
                    keuzes in gewone taal uit. Zo kun je ervaren hoe het werkt
                    en gerichte feedback geven. Die verwerk ik binnen de
                    afgesproken omvang.
                </p>
                <p class="mt-3 leading-7 text-slate-600">
                    Ik werk zorgvuldig door aan de details van de afgesproken
                    oplossing: kloppen de vervolgstappen, zijn keuzes
                    begrijpelijk en is de bediening prettig op mobiel en
                    desktop? Die afwerking hoort voor mij bij het bouwen.
                </p>
            </section>
            <section aria-labelledby="oplevering" class="mt-10">
                <h2 id="oplevering" class="text-lg font-semibold">
                    Van bouwen tot beheren
                </h2>
                <p class="mt-3 leading-7 text-slate-600">
                    Ik kan ook de hosting en het online zetten verzorgen. We
                    maken vooraf afspraken over de oplevering, de technische
                    omgeving en eventueel verder beheer. Uitbreidingen en nieuwe
                    functies bespreken we als afzonderlijk werk.
                </p>
            </section>
            <PublicButtonLink href="/contact" class="mt-8">
                Bespreek je idee
            </PublicButtonLink>
        </article>
    </PublicLayout>
</template>
