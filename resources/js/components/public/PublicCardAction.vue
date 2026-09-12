<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{ href: string }>();
</script>

<template>
    <Link
        :href="href"
        class="group/action inline-flex w-fit items-center gap-2 rounded-sm font-semibold text-slate-900 transition-colors duration-150 ease-out hover:text-slate-600 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-600 motion-reduce:transition-none"
    >
        <slot />
        <span
            aria-hidden="true"
            class="text-amber-600 transition-transform duration-150 ease-out group-hover/action:translate-x-0.5 motion-reduce:transition-none"
            >→</span
        >
    </Link>
</template>
