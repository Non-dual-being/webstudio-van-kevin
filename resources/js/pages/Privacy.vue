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
                gegevens om je vraag te behandelen. Hieronder lees je wat er met
                je gegevens gebeurt en hoe je daarover contact kunt opnemen.
            </p>
            <section
                aria-labelledby="contactgegevens"
                class="mt-10 border-l-2 border-amber-600 pl-5"
            >
                <h2 id="contactgegevens" class="text-lg font-semibold">
                    Je vraag en contactgegevens
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Het formulier vraagt om je naam, e-mailadres en bericht. Een
                    dienst kiezen is optioneel. Ik gebruik deze gegevens om te
                    begrijpen waarmee ik je kan helpen en om te antwoorden.
                    Zonder de verplichte velden kun je het formulier niet
                    versturen. Deel alleen wat nodig is voor je vraag, dus geen
                    wachtwoorden, identiteitsbewijzen of gevoelige
                    privégegevens.
                </p>
                <p class="mt-4 leading-7 text-slate-600">
                    De aanvraag wordt op de website opgeslagen en per e-mail
                    aangeboden aan mijn zakelijke mailbox. De website probeert
                    ook een ontvangstbevestiging naar jou te sturen. Die bevat
                    je berichttekst niet. Ik houd bij of de verwerking van die
                    mails lukt, zodat een storing kan worden onderzocht.
                </p>
                <p class="mt-4 leading-7 text-slate-600">
                    Gaat je vraag over een mogelijke opdracht voor jezelf, dan
                    is de grondslag het op jouw verzoek voorbereiden van een
                    overeenkomst. Bij andere vragen, ook namens een organisatie,
                    is mijn gerechtvaardigd belang om zakelijke vragen te kunnen
                    beantwoorden de grondslag. Je aanvraag wordt niet gebruikt
                    om je aan te melden voor een nieuwsbrief.
                </p>
            </section>
            <section aria-labelledby="veiligheid" class="mt-10">
                <h2 id="veiligheid" class="text-lg font-semibold">
                    Een werkende en veilige website
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    De website gebruikt functionele cookies om je bezoek te
                    herkennen, het formulier te beveiligen en dubbele
                    inzendingen te voorkomen. Bij je sessie worden ook je
                    IP-adres en informatie over je browser verwerkt. Ingevulde
                    gegevens kunnen kort worden bewaard om ze na een invoerfout
                    opnieuw te tonen.
                </p>
                <p class="mt-4 leading-7 text-slate-600">
                    Om spam te beperken gebruikt de website tijdelijk afgeleide
                    herkenningswaarden van IP- en e-mailadressen. Die zijn niet
                    hetzelfde als anonieme gegevens. Technische foutinformatie
                    helpt bij het opsporen van storingen. Mijn gerechtvaardigd
                    belang hierbij is een bereikbare website en bescherming
                    tegen misbruik.
                </p>
                <p class="mt-4 leading-7 text-slate-600">
                    Er zijn geen advertentie- of analysetrackers aan deze
                    website toegevoegd. Voor ingelogde gebruikers kan de browser
                    weergavevoorkeuren onthouden. Er worden geen profielen
                    opgebouwd voor reclame en er worden geen uitsluitend
                    geautomatiseerde beslissingen over je genomen met juridische
                    of vergelijkbaar grote gevolgen.
                </p>
            </section>
            <section aria-labelledby="ontvangers" class="mt-10">
                <h2 id="ontvangers" class="text-lg font-semibold">
                    Wie je gegevens verwerkt
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Kevin Webstudio gebruikt je aanvraag om je te helpen.
                    Dienstverleners voor de website en zakelijke e-mail
                    verwerken daarvoor gegevens. E-mail wordt ook verwerkt door
                    de aanbieder van de mailbox waarnaar die wordt verstuurd. Je
                    aanvraag is niet openbaar beschikbaar.
                </p>
            </section>
            <section aria-labelledby="bewaren" class="mt-10">
                <h2 id="bewaren" class="text-lg font-semibold">
                    Hoe lang gegevens nodig zijn
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Voor de bewaartijd tellen het afhandelen van je vraag,
                    noodzakelijke opvolging en het oplossen van eventuele
                    verzendproblemen. De kopie op de website dient voor de
                    verwerking van het formulier; correspondentie in de mailbox
                    dient voor het verdere contact. Beveiligingsgegevens zijn
                    alleen nodig zolang ze helpen om misbruik of storingen te
                    onderzoeken.
                </p>
                <p class="mt-4 leading-7 text-slate-600">
                    Komt er een opdracht uit voort, dan kunnen gegevens die voor
                    die opdracht nodig zijn in de klantadministratie
                    terechtkomen. Daar kunnen andere bewaarplichten voor gelden.
                    Een verzoek om verwijdering wordt per soort gegeven
                    beoordeeld, zodat duidelijk is wat kan worden verwijderd en
                    wat nog nodig is.
                </p>
            </section>
            <section aria-labelledby="rechten" class="mt-10">
                <h2 id="rechten" class="text-lg font-semibold">
                    Je rechten en contact
                </h2>
                <p class="mt-2 leading-7 text-slate-600">
                    Je kunt vragen om inzage, correctie of verwijdering van je
                    gegevens, of om beperking van het gebruik. Je kunt bezwaar
                    maken tegen verwerking op basis van een gerechtvaardigd
                    belang. In de gevallen waarin dat recht geldt, kun je ook
                    vragen om je gegevens in een overdraagbaar formaat te
                    ontvangen. Mail hiervoor naar
                    <a
                        href="mailto:info@kevinwebstudio.nl"
                        class="rounded-sm text-slate-900 underline underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-600"
                        >info@kevinwebstudio.nl</a
                    >.
                </p>
                <p class="mt-4 leading-7 text-slate-600">
                    Op een privacyverzoek krijg je binnen één maand een reactie.
                    Als verlenging nodig en toegestaan is, hoor je binnen die
                    maand waarom. Soms is aanvullende informatie nodig om vast
                    te stellen dat het verzoek over jouw gegevens gaat.
                </p>
                <p class="mt-4 leading-7 text-slate-600">
                    Je kunt ook een klacht indienen bij een
                    privacytoezichthouder, bijvoorbeeld in het land waar je
                    woont of werkt. De
                    <a
                        href="https://www.edpb.europa.eu/about-edpb/our-members_en"
                        class="rounded-sm text-slate-900 underline underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-amber-600"
                        >Europese lijst van privacytoezichthouders</a
                    >
                    helpt je de juiste instantie te vinden.
                </p>
            </section>
        </article>
    </PublicLayout>
</template>
