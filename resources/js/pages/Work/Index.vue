<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineOptions({ layout: PublicLayout });
</script>

<template>
    <Head title="Werk" />

    <section aria-labelledby="page-title">
        <h1 id="page-title" class="text-4xl font-semibold tracking-tight">
            Werk
        </h1>
        <p class="mt-5 max-w-2xl text-lg leading-8 text-neutral-600">
            Hier komt binnenkort een selectie van websites, dashboards en
            webshops.
        </p>
    </section>
</template>
