<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
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
                    Ik ben Kevin. Ik bouw websites en webapplicaties en duik
                    graag de techniek in om een lastig proces eenvoudiger te
                    maken.
                </p>
                <p>
                    Persoonlijke samenwerking vind ik belangrijk. Ik luister
                    naar hoe je echt werkt, let op de kleine details en leg
                    technische keuzes uit in gewone taal. Zo ontstaat iets dat
                    niet alleen functioneert, maar ook prettig voelt om te
                    gebruiken en beheren.
                </p>
            </div>
            <section
                aria-labelledby="aandacht"
                class="mt-10 border-l-2 border-amber-600 pl-5"
            >
                <h2 id="aandacht" class="text-lg font-semibold">
                    Aandacht boven haast
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Ik blijf leren, stel vragen en kies liever voor een
                    begrijpelijke oplossing die past dan voor techniek om de
                    techniek.
                </p>
            </section>
        </article>
    </PublicLayout>
</template>
