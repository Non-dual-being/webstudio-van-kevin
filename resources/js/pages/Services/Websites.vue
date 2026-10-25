<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicButtonLink from '@/components/public/PublicButtonLink.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Websites" />
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
                    Wat we tijdens de kennismaking afspreken
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Denk aan een dienstenoverzicht, een portfolio of een pagina
                    die veelgestelde vragen beantwoordt. We bespreken je
                    bezoekers en spreken de pagina’s, functies,
                    contactmogelijkheden en aanlevering van teksten en beelden
                    af, met aandacht voor gebruik op mobiel en desktop.
                </p>
                <p class="mt-3 leading-7 text-slate-600">
                    Zelf teksten en beelden beheren is een optie die we vooraf
                    afspreken. Ook leggen we vast hoe overdracht en latere
                    wijzigingen worden geregeld.
                </p>
            </section>
            <p class="mt-8 font-semibold text-slate-950">
                Prijs na projectbespreking
            </p>
            <PublicButtonLink href="/contact" class="mt-4">
                Bespreek je website
            </PublicButtonLink>
        </article>
    </PublicLayout>
</template>
