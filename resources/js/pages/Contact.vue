<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Contact" />
    <PublicLayout>
        <section class="max-w-3xl">
            <p
                class="text-sm font-semibold tracking-wide text-amber-700 uppercase"
            >
                Eerst even kennismaken
            </p>
            <h1 class="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl">
                Contact
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                Heb je een idee voor een website of systeem, of loopt een
                bestaand proces niet lekker? Binnenkort komt hier een eenvoudige
                manier om contact op te nemen. Een eerste gesprek begint vooral
                met goed luisteren en de juiste vragen stellen.
            </p>
            <p
                class="mt-8 border-l-2 border-amber-600 pl-5 leading-7 text-slate-600"
            >
                Er is bewust nog geen formulier: contactverwerking en e-mail
                volgen in een latere stap.
            </p>
        </section>
    </PublicLayout>
</template>
