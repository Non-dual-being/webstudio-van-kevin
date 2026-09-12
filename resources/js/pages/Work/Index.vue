<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ProjectCard from '@/components/public/ProjectCard.vue';
import PublicButtonLink from '@/components/public/PublicButtonLink.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { PortfolioProject } from '@/types/portfolio';

defineProps<{ projects: PortfolioProject[] }>();

const categories = [
    {
        title: 'Websites',
        text: 'Publieke websites waarin inhoud, toegankelijkheid en een heldere route voor bezoekers centraal staan.',
    },
    {
        title: 'Dashboards',
        text: 'Beheeromgevingen die gegevens en terugkerende werkzaamheden overzichtelijk maken.',
    },
    {
        title: 'Webshops',
        text: 'Verkoopomgevingen en catalogi met aandacht voor producten, beheer en gebruiksgemak.',
    },
    {
        title: 'Formulieren en boekingssystemen',
        text: 'Praktische systemen voor aanvragen en afspraken, afgestemd op het proces erachter.',
    },
];
</script>

<template>
    <Head title="Werk en projecten" />
    <PublicLayout>
        <header class="max-w-3xl">
            <span
                aria-hidden="true"
                class="block h-1 w-10 rounded-full bg-amber-600"
            />
            <h1
                class="mt-5 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"
            >
                Werk
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                Een blik op digitale oplossingen en de keuzes erachter. Ontdek
                hoe een aanvraagproces vorm krijgt en welke functies daarbij
                samenkomen.
            </p>
        </header>

        <section aria-labelledby="projecten" class="mt-14">
            <h2 id="projecten" class="text-2xl font-semibold">Projecten</h2>
            <div class="mt-6 grid gap-5">
                <ProjectCard
                    v-for="project in projects"
                    :key="project.slug"
                    :project="project"
                />
            </div>
        </section>

        <section aria-labelledby="projectcategorieen" class="mt-14">
            <h2 id="projectcategorieen" class="text-2xl font-semibold">
                Soorten projecten
            </h2>
            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <article
                    v-for="category in categories"
                    :key="category.title"
                    class="rounded-xl border border-stone-200 bg-white p-6 transition-[border-color,box-shadow] duration-150 ease-out hover:border-slate-300 hover:shadow-sm motion-reduce:transition-none sm:p-7"
                >
                    <span
                        aria-hidden="true"
                        class="block h-1 w-8 rounded-full bg-amber-600"
                    />
                    <h3 class="mt-5 text-lg font-semibold text-slate-950">
                        {{ category.title }}
                    </h3>
                    <p class="mt-3 leading-7 text-slate-600">
                        {{ category.text }}
                    </p>
                </article>
            </div>
        </section>

        <aside class="mt-14 max-w-3xl rounded-xl bg-stone-100 p-7 sm:p-8">
            <h2 class="text-lg font-semibold text-slate-950">
                Op zoek naar wat ik voor je kan bouwen?
            </h2>
            <p class="mt-2 leading-7 text-slate-600">
                Op de dienstenpagina staat het aanbod voor nieuwe projecten
                overzichtelijk bij elkaar.
            </p>
            <PublicButtonLink href="/diensten" variant="secondary" class="mt-5">
                Bekijk de diensten
            </PublicButtonLink>
        </aside>
    </PublicLayout>
</template>
