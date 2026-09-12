<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
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
                Webshops en productcatalogi
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                Ik maak webshops en catalogi waarin producten duidelijk worden
                gepresenteerd en klanten prettig hun weg vinden. Ook het beheer
                achter de schermen krijgt de aandacht die het verdient.
            </p>
            <section
                aria-labelledby="werkwijze"
                class="mt-10 border-l-2 border-amber-600 pl-5"
            >
                <h2 id="werkwijze" class="text-lg font-semibold">
                    Passend bij je assortiment
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Productstructuur, bestelproces en beheer worden afgestemd op
                    hoe jij verkoopt, zonder onnodige functies toe te voegen.
                </p>
            </section>
        </article>
    </PublicLayout>
</template>
