<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicButtonLink from '@/components/public/PublicButtonLink.vue';
import PublicCardAction from '@/components/public/PublicCardAction.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { PortfolioProject } from '@/types/portfolio';

defineProps<{ project: PortfolioProject; demoUrl: string | null }>();
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
            <a
                v-if="demoUrl"
                :href="demoUrl"
                class="mt-6 inline-flex min-h-11 items-center rounded-md border border-slate-900 bg-slate-900 px-5 py-2.5 font-semibold text-white hover:bg-slate-800 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-600"
            >
                {{ project.demoLinkLabel }}
            </a>
            <a
                v-if="project.projectUrl"
                :href="project.projectUrl"
                class="mt-6 inline-flex min-h-11 items-center rounded-md border border-slate-900 bg-slate-900 px-5 py-2.5 font-semibold text-white hover:bg-slate-800 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-600"
            >
                {{ project.projectLinkLabel }}
            </a>
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
            aria-labelledby="projectstappen"
            class="mt-14 rounded-xl bg-stone-100 p-6 sm:p-8"
        >
            <h2
                id="projectstappen"
                class="text-2xl font-semibold tracking-tight"
            >
                {{ project.stepsTitle }}
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
                {{ project.featuresTitle }}
            </h2>
            <div class="mt-6 grid gap-8">
                <article
                    v-for="(feature, index) in project.features"
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
                    <p class="mt-3 max-w-3xl leading-7 text-slate-600">
                        {{ feature.text }}
                    </p>
                    <figure v-if="project.images[index]" class="mt-6">
                        <picture>
                            <source
                                media="(max-width: 639px)"
                                :srcset="project.images[index].mobileSrc"
                                :width="project.images[index].mobileWidth"
                                :height="project.images[index].mobileHeight"
                            />
                            <img
                                :src="project.images[index].src"
                                :alt="project.images[index].alt"
                                :width="project.images[index].width"
                                :height="project.images[index].height"
                                loading="lazy"
                                decoding="async"
                                class="h-auto w-full rounded-lg border border-stone-200"
                            />
                        </picture>
                        <figcaption
                            class="mt-3 text-sm leading-6 text-slate-600"
                        >
                            {{ project.images[index].caption }}
                        </figcaption>
                    </figure>
                </article>
            </div>
        </section>

        <section
            v-if="demoUrl"
            aria-labelledby="proberen"
            class="mt-14 rounded-xl bg-stone-100 p-6 sm:p-8"
        >
            <h2 id="proberen" class="text-2xl font-semibold tracking-tight">
                Probeer het zelf
            </h2>
            <ol
                class="mt-6 grid list-decimal gap-5 pl-5 leading-7 text-slate-700 sm:grid-cols-3 sm:gap-10"
            >
                <li v-for="step in project.trySteps" :key="step">{{ step }}</li>
            </ol>
            <p class="mt-6 max-w-3xl text-sm leading-6 text-slate-600">
                {{ project.demoNote }}
            </p>
            <a
                :href="demoUrl"
                class="mt-6 inline-flex min-h-11 items-center rounded-md border border-slate-900 bg-slate-900 px-5 py-2.5 font-semibold text-white hover:bg-slate-800 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-600"
                >{{ project.demoStartLabel }}</a
            >
        </section>

        <section aria-labelledby="projectbereik" class="mt-14 max-w-3xl">
            <h2
                id="projectbereik"
                class="text-2xl font-semibold tracking-tight"
            >
                {{ project.scopeTitle }}
            </h2>
            <p class="mt-4 leading-7 text-slate-600">{{ project.scope }}</p>
            <p
                v-if="!demoUrl && project.demoUnavailableNote"
                class="mt-4 leading-7 text-slate-600"
            >
                {{ project.demoUnavailableNote }}
            </p>
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
