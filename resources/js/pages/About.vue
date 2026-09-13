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
                    Ik ben Kevin. Ik vind het mooi wanneer een ingewikkelde
                    vraag uitmondt in iets dat vanzelfsprekend werkt. Een
                    formulier dat je door de juiste keuzes helpt, of een website
                    waarop je snel vindt wat je zoekt.
                </p>
                <p>
                    Daarom wil ik begrijpen hoe mensen een website of systeem
                    gebruiken. Waar twijfelen ze? Welke informatie hebben ze
                    nodig? Die vragen helpen me om keuzes in inhoud, ontwerp en
                    techniek uit te leggen en gericht te bouwen.
                </p>
            </div>
            <section
                aria-labelledby="aandacht"
                class="mt-10 border-l-2 border-amber-600 pl-5"
            >
                <h2 id="aandacht" class="text-lg font-semibold">
                    Duidelijk voor jou en je bezoekers
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Ik duik graag in een technisch vraagstuk en leg je in gewone
                    taal uit wat de mogelijkheden en afwegingen zijn. Onderweg
                    bekijken we wat werkt en wat bijgesteld moet worden. Ook het
                    gebruik en beheer na oplevering nemen we mee in de
                    afspraken.
                </p>
            </section>
            <PublicButtonLink href="/contact" class="mt-8">
                Bespreek je idee
            </PublicButtonLink>
        </article>
    </PublicLayout>
</template>
