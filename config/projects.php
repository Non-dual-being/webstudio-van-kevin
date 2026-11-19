<?php

return [
    'onderwijsformulier' => [
        'slug' => 'onderwijsformulier',
        'title' => 'Onderwijsaanvragen: van formulier tot dashboard',
        'category' => 'Formulieren en dashboards',
        'summary' => 'Een onderwijsbezoek stap voor stap aanvragen en daarna terugvinden, aanpassen en opvolgen in één overzicht.',
        'intro' => 'Een onderwijsbezoek aanvragen vraagt om meer dan een datum en een naam. Dit systeem helpt scholen hun bezoek samen te stellen en geeft de organisatie overzicht bij de afhandeling. Van de eerste keuze in het formulier tot de aanvraag in het dashboard.',
        'challenge' => 'Welke programma’s passen bij de groep en de bezoekdag? Hoeveel leerlingen en begeleiders komen mee? En welke aanvragen wachten nog op bevestiging? Die keuzes moeten tijdens het aanvragen duidelijk zijn én bij de afhandeling bij elkaar blijven.',
        'solution' => 'Het formulier begeleidt de school door de samenhangende keuzes. De afgeronde aanvraag verschijnt vervolgens in het dashboard, waar de organisatie gegevens kan controleren, aantallen kan aanpassen en de status kan wijzigen. Zo worden ingewikkelde regels begrijpelijke handelingen.',
        'features' => [
            ['title' => 'Een bezoek samenstellen', 'text' => 'Kies een beschikbare datum en een passend programma. Daarna volgen de groepen, leerlingen en begeleiders. Het formulier geeft uitleg bij ongeldige keuzes en berekent een voorbeeldprijs, inclusief gekozen catering.'],
            ['title' => 'Een aanvraag opvolgen', 'text' => 'De aanvraag komt binnen met status “In optie”. Zoek of filter in het overzicht, open de details en pas bijvoorbeeld contactgegevens, aantallen of opmerkingen aan. De voorbeeldprijs wordt opnieuw berekend; definitief maken kan alleen als de aanvraag aan de regels voldoet.'],
        ],
        'steps' => [
            ['title' => 'Datum en programma', 'text' => 'De school kiest een beschikbare bezoekdag en een programma dat bij het onderwijs past.'],
            ['title' => 'Groepen en prijs', 'text' => 'Leerlingen, begeleiding en catering maken het bezoek compleet. De voorbeeldprijs volgt de keuzes.'],
            ['title' => 'Van aanvraag naar overzicht', 'text' => 'Na afronden staat de aanvraag in optie. Een directe link opent precies die aanvraag in het dashboard.'],
            ['title' => 'Controleren en bevestigen', 'text' => 'De organisatie bekijkt de details, verwerkt wijzigingen en maakt een geldige aanvraag definitief.'],
        ],
        'images' => [
            [
                'src' => '/images/projects/onderwijsformulier/formulier.webp',
                'mobileSrc' => '/images/projects/onderwijsformulier/formulier-mobiel.webp',
                'width' => 1200,
                'height' => 800,
                'alt' => 'Onderwijsformulier met primair onderwijs, een ochtendprogramma en de groepsselectie voor een fictief bezoek.',
                'caption' => 'Het formulier brengt programma, groepen en aantallen stap voor stap bij elkaar.',
            ],
            [
                'src' => '/images/projects/onderwijsformulier/dashboard.webp',
                'mobileSrc' => '/images/projects/onderwijsformulier/dashboard-mobiel.webp',
                'width' => 1200,
                'height' => 800,
                'alt' => 'Demodashboard met fictieve onderwijsaanvragen, statusfilters en voorbeeldprijzen.',
                'caption' => 'In het dashboard zie je welke aanvragen in optie, definitief of afgewezen zijn. Open een aanvraag om die op te volgen.',
            ],
        ],
        'trySteps' => [
            'Stel een fictief onderwijsbezoek samen op een beschikbare toekomstige datum. Vul uitsluitend verzonnen gegevens in.',
            'Rond de aanvraag af en kies “Bekijk in het demodashboard”. Je opent meteen je eigen aanvraag.',
            'Pas geldige aantallen aan, sla op en probeer “Definitief maken”. Via de teruglink ga je weer naar deze case.',
        ],
        'demoNote' => 'De demo begint met 100 fictieve scholen. Prijzen zijn voorbeelden; er volgt geen echte boeking of e-mail. Je invoer wordt tijdelijk verwerkt voor de demo. Blijf in hetzelfde tabblad: vernieuwen, opnieuw starten of opnieuw openen wist je eigen wijzigingen.',
        'scope' => 'De beelden en demo tonen het traject van aanvraag tot opvolging. In het dashboard zijn programma, bezoekdatum en onderwijskeuze alleen leesbaar. Roosterplanning, personeelsplanning, exports, facturen en echte e-mail maken geen deel uit van deze demo.',
        'stack' => ['Vue 3', 'TypeScript', 'Vite', 'PHP', 'Flatpickr'],
        'contactTitle' => 'Ook een aanvraagproces met veel keuzes?',
        'contactText' => 'Vertel welke gegevens, regels en vervolgstappen in jouw proces samenkomen. Dan kijken we hoe een formulier en een overzicht voor de afhandeling op elkaar kunnen aansluiten.',
    ],
];
