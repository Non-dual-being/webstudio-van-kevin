<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicButtonLink from '@/components/public/PublicButtonLink.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Webshops en productcatalogi" />
    <PublicLayout>
        <article class="max-w-3xl">
            <span
                aria-hidden="true"
                class="block h-1 w-10 rounded-full bg-amber-600"
            />
            <h1
                class="mt-5 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"
            >
                Je producten duidelijk in beeld
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                Wil je je assortiment online laten zien of klanten laten
                bestellen? Ik kan een catalogus bouwen met categorieën,
                productfoto’s, kenmerken en een aanvraagmogelijkheid. Een
                aanvraag is nog geen bestelling. In een webshop kunnen klanten
                daadwerkelijk online bestellen; daarvoor spreken we ook het
                bestelproces en de afhandeling af.
            </p>
            <section
                aria-labelledby="werkwijze"
                class="mt-10 border-l-2 border-amber-600 pl-5"
            >
                <h2 id="werkwijze" class="text-lg font-semibold">
                    Wat we tijdens de kennismaking afspreken
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    We bespreken je assortiment, productinformatie en
                    verkoopwijze. Voor online bestellen spreken we af wat nodig
                    is voor betalen, voorraad, verzending of afhalen en
                    retourafhandeling. Externe koppelingen onderzoeken we
                    vooraf; deze onderdelen worden per opdracht gekozen.
                </p>
                <p class="mt-3 leading-7 text-slate-600">
                    Zelf producten, teksten en beelden beheren is een optie die
                    we vooraf afspreken. We leggen ook vast wie de inhoud
                    aanlevert en hoe beheer en overdracht worden geregeld.
                </p>
            </section>
            <p class="mt-8 font-semibold text-slate-950">
                Prijs na kennismaking
            </p>
            <PublicButtonLink href="/contact" class="mt-4">
                Bespreek je assortiment
            </PublicButtonLink>
        </article>
    </PublicLayout>
</template>
