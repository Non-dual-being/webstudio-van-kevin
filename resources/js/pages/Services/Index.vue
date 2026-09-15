<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicCardAction from '@/components/public/PublicCardAction.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

const services = [
    {
        title: 'Websites',
        text: 'Is het voor bezoekers niet duidelijk wat je doet? Ik kan je aanbod, werk en contactmogelijkheden een logische plek geven op een heldere website.',
        href: '/diensten/websites',
        linkLabel: 'Bekijk websites',
    },
    {
        title: 'Dashboards en beheeromgevingen',
        text: 'Mis je overzicht in losse bestanden of aanvragen? We bekijken welke gegevens en handelingen je in één beheeromgeving nodig hebt, zoals zoeken, filteren en een status bijhouden.',
        href: '/diensten/dashboards',
        linkLabel: 'Bekijk dashboards',
    },
    {
        title: 'Webshops en productcatalogi',
        text: 'Wil je producten laten zien of online verkopen? Denk aan categorieën, productinformatie en een passende stap naar aanvragen of bestellen. Betalingen en koppelingen stemmen we vooraf af.',
        href: '/diensten/webshops',
        linkLabel: 'Bekijk webshops',
    },
    {
        title: 'Formulieren en boekingssystemen',
        text: 'Moet je bij aanvragen vaak informatie navragen? Een begeleid formulier kan passende vervolgvragen, invoercontroles en een overzicht vóór verzending combineren. We bepalen samen welke regels nodig zijn.',
        href: '/contact',
        linkLabel: 'Bespreek je proces',
    },
];
</script>

<template>
    <Head title="Diensten" />
    <PublicLayout>
        <header class="max-w-3xl">
            <span
                aria-hidden="true"
                class="block h-1 w-10 rounded-full bg-amber-600"
            />
            <h1
                class="mt-5 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"
            >
                Wat wil je makkelijker maken?
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                Een bezoeker moet je aanbod kunnen begrijpen, een aanvraag goed
                kunnen invullen of een product kunnen vinden. Misschien wil je
                zelf meer overzicht in je werk. Ik help je bepalen wat daarvoor
                nodig is en welke onderdelen we bouwen.
            </p>
        </header>

        <section aria-labelledby="dienstenaanbod" class="mt-14">
            <h2 id="dienstenaanbod" class="text-2xl font-semibold">
                Waarmee ik kan helpen
            </h2>
            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <article
                    v-for="service in services"
                    :key="service.title"
                    class="flex h-full flex-col rounded-xl border border-stone-200 bg-white p-6 transition-[border-color,box-shadow] duration-150 ease-out hover:border-slate-300 hover:shadow-sm motion-reduce:transition-none sm:p-7"
                >
                    <span
                        aria-hidden="true"
                        class="block h-1 w-8 rounded-full bg-amber-600"
                    />
                    <h3 class="mt-5 text-lg font-semibold text-slate-950">
                        {{ service.title }}
                    </h3>
                    <p class="mt-3 mb-6 leading-7 text-slate-600">
                        {{ service.text }}
                    </p>
                    <footer class="mt-auto border-t border-stone-200 pt-5">
                        <PublicCardAction :href="service.href">
                            {{ service.linkLabel }}
                        </PublicCardAction>
                    </footer>
                </article>
            </div>
        </section>
    </PublicLayout>
</template>
