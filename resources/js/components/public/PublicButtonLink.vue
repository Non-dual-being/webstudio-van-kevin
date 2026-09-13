<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        href: string;
        variant?: 'primary' | 'secondary';
    }>(),
    { variant: 'primary' },
);

const variantClasses = computed(() =>
    props.variant === 'primary'
        ? 'public-button-link--primary border-slate-900 bg-slate-900 text-white'
        : 'public-button-link--secondary border-slate-700 bg-stone-50 text-slate-900',
);
</script>

<template>
    <Link
        :href="href"
        class="public-button-link relative isolate inline-flex min-h-11 items-center justify-center overflow-hidden rounded-md border px-5 py-2.5 font-semibold focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-600"
        :class="variantClasses"
    >
        <span class="relative z-10">
            <slot />
        </span>
        <span
            v-if="variant === 'secondary'"
            aria-hidden="true"
            class="public-button-link__fill pointer-events-none absolute inset-0 z-20 inline-flex items-center justify-center bg-slate-900 px-5 py-2.5 text-white"
        >
            <slot />
        </span>
    </Link>
</template>

<style scoped>
.public-button-link--primary {
    transition: background-color 160ms ease-out;
}

.public-button-link__fill {
    clip-path: inset(0 100% 0 0);
    transition: clip-path 220ms ease-out;
}

@media (hover: hover) and (pointer: fine) {
    .public-button-link--primary:hover {
        background-color: var(--color-slate-800);
    }

    .public-button-link--secondary:hover .public-button-link__fill {
        clip-path: inset(0);
    }
}

.public-button-link--primary:active {
    background-color: var(--color-slate-800);
}

.public-button-link--secondary:active .public-button-link__fill {
    clip-path: inset(0);
}

@media (prefers-reduced-motion: reduce) {
    .public-button-link--primary,
    .public-button-link__fill {
        transition: none;
    }
}
</style>
