<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Privacy" />
    <PublicLayout>
        <article class="max-w-3xl">
            <span
                aria-hidden="true"
                class="block h-1 w-10 rounded-full bg-amber-600"
            />
            <h1
                class="mt-5 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"
            >
                Privacy
            </h1>
            <p class="mt-6 text-lg leading-8 text-slate-700">
                Neem je contact op met Kevin Webstudio, dan gebruik ik je
                gegevens om je aanvraag te bekijken en erop te reageren.
            </p>
            <section
                aria-labelledby="huidigesituatie"
                class="mt-10 border-l-2 border-amber-600 pl-5"
            >
                <h2 id="huidigesituatie" class="text-lg font-semibold">
                    Het contactformulier
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Je naam, e-mailadres, bericht en eventuele dienstkeuze
                    worden opgeslagen. Ik ontvang je aanvraag per e-mail; op het
                    opgegeven adres ontvang je een beperkte
                    ontvangstbevestiging. Die bevestiging bevat je berichttekst
                    niet.
                </p>
                <p class="mt-4 leading-7 text-slate-600">
                    De website gebruikt een technische sessiecookie om het
                    formulier te beveiligen en dubbele inzendingen te voorkomen.
                    Voor spambeperking worden tijdelijk afgeleide
                    herkenningswaarden van IP- en e-mailadres gebruikt. De
                    aanvraagopslag bevat geen volledig browserprofiel of
                    IP-adres. Ook de verwerkingsstatus van de twee mails wordt
                    bijgehouden om fouten te kunnen herstellen.
                </p>
                <p class="mt-4 leading-7 text-slate-600">
                    Je aanvraag is niet openbaar beschikbaar. Je gegevens worden
                    niet gebruikt voor een nieuwsbrief. Heb je een vraag over je
                    gegevens of wil je ze laten corrigeren of verwijderen? Mail
                    naar
                    <a
                        href="mailto:info@kevinwebstudio.nl"
                        class="rounded-sm text-slate-900 underline underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-600"
                        >info@kevinwebstudio.nl</a
                    >.
                </p>
            </section>
        </article>
    </PublicLayout>
</template>
