<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicButtonLink from '@/components/public/PublicButtonLink.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Webapplicaties op maat" />
    <PublicLayout>
        <article class="max-w-3xl">
            <span
                aria-hidden="true"
                class="block h-1 w-10 rounded-full bg-amber-600"
            />
            <h1
                class="mt-5 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"
            >
                Webapplicaties op maat voor jouw werk
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                Komen aanvragen onvolledig binnen of staan taken en afspraken
                verspreid over losse bestanden? Ik kan een formulier met
                passende vervolgvragen en invoercontroles bouwen, of een
                dashboard waarin je aanvragen zoekt, filtert en een status
                bijhoudt.
            </p>
            <section
                aria-labelledby="werkwijze"
                class="mt-10 border-l-2 border-amber-600 pl-5"
            >
                <h2 id="werkwijze" class="text-lg font-semibold">
                    Wat we tijdens de kennismaking afspreken
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    We brengen je werkproces in kaart en spreken af wie ermee
                    werkt, welke gegevens nodig zijn en welke handelingen,
                    regels en uitzonderingen de applicatie ondersteunt. Gewenste
                    koppelingen onderzoeken we voordat we de omvang bepalen.
                </p>
                <p class="mt-3 leading-7 text-slate-600">
                    Ook beheer, toegangsrechten en overdracht spreken we af.
                    Zelf teksten en beelden aanpassen is een optie die we vooraf
                    vastleggen.
                </p>
            </section>
            <p class="mt-8 font-semibold text-slate-950">
                Prijs na projectbespreking
            </p>
            <PublicButtonLink href="/contact" class="mt-4">
                Bespreek je webapplicatie
            </PublicButtonLink>
        </article>
    </PublicLayout>
</template>
