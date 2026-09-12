<script setup lang="ts">
import PublicCardAction from '@/components/public/PublicCardAction.vue';
import { show } from '@/routes/work';
import type { PortfolioProject } from '@/types/portfolio';

defineProps<{ project: PortfolioProject }>();
</script>

<template>
    <article
        class="rounded-xl border border-stone-200 bg-white p-6 transition-[border-color,box-shadow] duration-150 ease-out hover:border-slate-300 hover:shadow-sm motion-reduce:transition-none sm:p-8"
    >
        <p class="flex items-center gap-2 text-sm font-medium text-slate-600">
            <span
                aria-hidden="true"
                class="size-2 shrink-0 rounded-full bg-amber-600"
            />
            {{ project.category }}
        </p>
        <h3 class="mt-4 text-2xl font-semibold tracking-tight text-slate-950">
            {{ project.title }}
        </h3>
        <p class="mt-3 max-w-2xl leading-7 text-slate-600">
            {{ project.summary }}
        </p>
        <PublicCardAction :href="show.url(project.slug)" class="mt-6">
            Bekijk de case
            <span class="sr-only">{{ project.title }}</span>
        </PublicCardAction>
    </article>
</template>
