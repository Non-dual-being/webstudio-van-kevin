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
                bestellen? Een catalogus kan helpen om producten te bekijken en
                een aanvraag te doen. Voor directe verkoop is ook een passend
                bestelproces nodig. Ik denk met je mee over welke vorm bij je
                producten en werkwijze past.
            </p>
            <section
                aria-labelledby="werkwijze"
                class="mt-10 border-l-2 border-amber-600 pl-5"
            >
                <h2 id="werkwijze" class="text-lg font-semibold">
                    Afstemmen op hoe je verkoopt
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Denk aan duidelijke categorieën, productfoto’s, kenmerken en
                    varianten. We spreken af wat je zelf wilt beheren en of
                    betalingen, voorraad, verzending of externe koppelingen
                    nodig zijn. Die keuzes bepalen welke onderdelen we kunnen
                    bouwen binnen de afgesproken opdracht.
                </p>
            </section>
            <PublicButtonLink href="/contact" class="mt-8">
                Bespreek je assortiment
            </PublicButtonLink>
        </article>
    </PublicLayout>
</template>
