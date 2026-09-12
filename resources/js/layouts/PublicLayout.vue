<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import BrandMark from '@/components/public/BrandMark.vue';

const navigation = [
    { label: 'Home', href: '/' },
    { label: 'Werk', href: '/werk' },
    { label: 'Diensten', href: '/diensten' },
    { label: 'Over mij', href: '/over-mij' },
    { label: 'Contact', href: '/contact' },
    { label: 'Privacy', href: '/privacy' },
];

const page = usePage();
const currentPath = computed(() => page.url.split('?')[0]);

const isCurrentPage = (href: string) => currentPath.value === href;
const isActive = (href: string) =>
    isCurrentPage(href) ||
    (href !== '/' && currentPath.value.startsWith(`${href}/`));
</script>

<template>
    <div class="flex min-h-screen flex-col bg-stone-50 text-slate-900">
        <header class="border-b border-stone-200 bg-stone-50">
            <div
                class="mx-auto flex max-w-6xl flex-col gap-5 px-5 py-5 md:flex-row md:items-center md:justify-between"
            >
                <Link
                    href="/"
                    class="flex w-fit items-center gap-2.5 rounded-sm text-lg font-semibold tracking-tight text-slate-950 transition-colors duration-150 ease-out hover:text-slate-600 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-600 motion-reduce:transition-none"
                >
                    <BrandMark class="size-11 shrink-0" />
                    <span>Kevin Webstudio</span>
                </Link>

                <nav aria-label="Hoofdnavigatie">
                    <ul
                        class="grid grid-cols-3 gap-x-4 gap-y-3 text-sm sm:flex sm:flex-wrap sm:gap-x-5"
                    >
                        <li v-for="item in navigation" :key="item.href">
                            <Link
                                :href="item.href"
                                :aria-current="
                                    isCurrentPage(item.href)
                                        ? 'page'
                                        : undefined
                                "
                                class="inline-block rounded-sm border-b-2 py-1 underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-700"
                                :class="
                                    isActive(item.href)
                                        ? 'border-amber-600 font-semibold text-slate-950'
                                        : 'border-transparent text-slate-600 hover:border-stone-300 hover:text-slate-950'
                                "
                            >
                                {{ item.label }}
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>

        <main class="mx-auto w-full max-w-6xl flex-1 px-5 py-12 sm:py-16">
            <slot />
        </main>

        <footer class="border-t border-stone-200">
            <div
                class="mx-auto flex max-w-6xl flex-col gap-2 px-5 py-7 text-sm text-slate-600 sm:flex-row sm:items-center sm:justify-between"
            >
                <p>&copy; {{ new Date().getFullYear() }} Kevin Webstudio</p>
                <p>Met aandacht gebouwd, helder uitgelegd.</p>
            </div>
        </footer>
    </div>
</template>
