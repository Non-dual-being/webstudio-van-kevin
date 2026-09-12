<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicButtonLink from '@/components/public/PublicButtonLink.vue';
import PublicCardAction from '@/components/public/PublicCardAction.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { PortfolioProject } from '@/types/portfolio';

defineProps<{ project: PortfolioProject }>();
</script>

<template>
    <Head :title="project.title">
        <meta name="description" :content="project.summary" />
    </Head>
    <PublicLayout>
        <PublicCardAction href="/werk">Alle projecten</PublicCardAction>
        <header class="mt-10 max-w-3xl">
            <span
                aria-hidden="true"
                class="block h-1 w-10 rounded-full bg-amber-600"
            />
            <p class="mt-5 text-sm font-medium text-slate-600">
                {{ project.category }}
            </p>
            <h1
                class="mt-3 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"
            >
                {{ project.title }}
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                {{ project.intro }}
            </p>
        </header>

        <div
            class="mt-14 grid gap-10 border-t border-stone-200 pt-10 md:grid-cols-2 md:gap-12"
        >
            <section aria-labelledby="uitdaging">
                <h2
                    id="uitdaging"
                    class="text-2xl font-semibold tracking-tight"
                >
                    De uitdaging
                </h2>
                <p class="mt-4 leading-7 text-slate-600">
                    {{ project.challenge }}
                </p>
            </section>
            <section aria-labelledby="oplossing">
                <h2
                    id="oplossing"
                    class="text-2xl font-semibold tracking-tight"
                >
                    De oplossing
                </h2>
                <p class="mt-4 leading-7 text-slate-600">
                    {{ project.solution }}
                </p>
            </section>
        </div>

        <section
            aria-labelledby="aanvraagproces"
            class="mt-14 rounded-xl bg-stone-100 p-6 sm:p-8"
        >
            <h2
                id="aanvraagproces"
                class="text-2xl font-semibold tracking-tight"
            >
                Van bezoekidee naar aanvraag
            </h2>
            <ol class="mt-7 grid gap-7 sm:grid-cols-2 lg:grid-cols-4">
                <li v-for="(step, index) in project.steps" :key="step.title">
                    <span
                        aria-hidden="true"
                        class="text-sm font-semibold text-amber-700"
                        >0{{ index + 1 }}</span
                    >
                    <h3 class="mt-3 font-semibold text-slate-950">
                        {{ step.title }}
                    </h3>
                    <p class="mt-2 leading-7 text-slate-600">{{ step.text }}</p>
                </li>
            </ol>
        </section>

        <section aria-labelledby="functies" class="mt-14">
            <h2 id="functies" class="text-2xl font-semibold tracking-tight">
                Belangrijke functies
            </h2>
            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <article
                    v-for="feature in project.features"
                    :key="feature.title"
                    class="rounded-xl border border-stone-200 bg-white p-6 sm:p-7"
                >
                    <span
                        aria-hidden="true"
                        class="block h-1 w-8 rounded-full bg-amber-600"
                    />
                    <h3 class="mt-5 text-lg font-semibold text-slate-950">
                        {{ feature.title }}
                    </h3>
                    <p class="mt-3 leading-7 text-slate-600">
                        {{ feature.text }}
                    </p>
                </article>
            </div>
        </section>

        <section aria-labelledby="achter-het-formulier" class="mt-14 max-w-3xl">
            <h2
                id="achter-het-formulier"
                class="text-2xl font-semibold tracking-tight"
            >
                Achter het formulier
            </h2>
            <p class="mt-4 leading-7 text-slate-600">{{ project.scope }}</p>
            <ul
                aria-label="Gebruikte technieken"
                class="mt-5 flex flex-wrap gap-2"
            >
                <li
                    v-for="technology in project.stack"
                    :key="technology"
                    class="rounded-md border border-stone-200 bg-white px-3 py-1.5 text-sm text-slate-600"
                >
                    {{ technology }}
                </li>
            </ul>
        </section>

        <section class="mt-14 max-w-3xl rounded-xl bg-stone-100 p-7 sm:p-9">
            <h2 class="text-2xl font-semibold tracking-tight text-slate-950">
                {{ project.contactTitle }}
            </h2>
            <p class="mt-3 leading-7 text-slate-600">
                {{ project.contactText }}
            </p>
            <PublicButtonLink href="/contact" class="mt-6"
                >Neem contact op</PublicButtonLink
            >
        </section>
    </PublicLayout>
</template>
