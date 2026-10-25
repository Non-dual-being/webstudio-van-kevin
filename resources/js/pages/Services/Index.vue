<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicCardAction from '@/components/public/PublicCardAction.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

const services = [
    {
        title: 'Websites',
        text: 'Begrijpen bezoekers niet goed wat je doet? Ik kan je diensten, portfolio en contactmogelijkheden samenbrengen op een heldere website.',
        agreement:
            'Tijdens de kennismaking spreken we de pagina’s, inhoud, functies en het beheer af.',
        href: '/diensten/websites',
        linkLabel: 'Bekijk websites',
    },
    {
        title: 'Webapplicaties op maat',
        text: 'Komen aanvragen onvolledig binnen of mis je overzicht? Ik kan een begeleid formulier, een dashboard of een beheeromgeving voor je werkproces bouwen.',
        agreement:
            'Tijdens de kennismaking spreken we de gebruikers, gegevens, regels, handelingen en eventuele koppelingen af.',
        href: '/diensten/dashboards',
        linkLabel: 'Bekijk webapplicaties',
    },
    {
        title: 'Webshops en productcatalogi',
        text: 'Wil je producten tonen of verkopen? Ik kan een catalogus met productinformatie en een aanvraagmogelijkheid bouwen, of een webshop waarin klanten daadwerkelijk bestellen.',
        agreement:
            'Tijdens de kennismaking spreken we assortiment, beheer en de gewenste bestel-, betaal- en leveronderdelen af.',
        href: '/diensten/webshops',
        linkLabel: 'Bekijk webshops en catalogi',
    },
    {
        title: 'Hosting, onderhoud en doorontwikkeling',
        text: 'Wil je ook de techniek achter je website laten verzorgen? Naast het bouwen kan ik hosting en het online zetten regelen, inclusief het inrichten van een VPS of dedicated server als dat bij je project past.',
        agreement:
            'We spreken vooraf af wat hosting, updates, back-ups, herstel en ondersteuning omvatten. Er is geen automatisch inbegrepen abonnement. Nieuwe functies en doorontwikkeling begroten we apart.',
        href: '/contact',
        linkLabel: 'Bespreek hosting en beheer',
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
                    <p class="mt-3 leading-7 text-slate-600">
                        {{ service.text }}
                    </p>
                    <p class="mt-3 mb-6 leading-7 text-slate-600">
                        {{ service.agreement }}
                    </p>
                    <footer class="mt-auto border-t border-stone-200 pt-5">
                        <p class="mb-3 text-sm font-semibold text-slate-700">
                            Prijs na projectbespreking
                        </p>
                        <PublicCardAction :href="service.href">
                            {{ service.linkLabel }}
                        </PublicCardAction>
                    </footer>
                </article>
            </div>
        </section>
        <section
            aria-labelledby="project-bespreken"
            class="mt-14 max-w-3xl border-t border-stone-200 pt-8"
        >
            <h2 id="project-bespreken" class="text-2xl font-semibold">
                Van eerste bericht naar duidelijke afspraken
            </h2>
            <p class="mt-4 leading-7 text-slate-600">
                Bekijk mijn werk en vertel via het contactformulier of
                info@kevinwebstudio.nl wat je wilt maken. De verdere afstemming
                verloopt per e-mail. Tijdens de projectbespreking bepalen we de
                omvang, planning en geldafspraken. Daarop baseer ik een
                persoonlijk voorstel en de prijs.
            </p>
        </section>
    </PublicLayout>
</template>
