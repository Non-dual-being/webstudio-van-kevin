<?php

return [
    'onderwijsformulier' => [
        'slug' => 'onderwijsformulier',
        'title' => 'Onderwijsformulier',
        'category' => 'Formulieren en boekingssystemen',
        'summary' => 'Een onderwijsbezoek aanvragen, van een passende datum en programma tot een prijsopgave en bevestiging van de aanvraag.',
        'intro' => 'Een publiek aanvraagformulier dat meebeweegt met de keuzes van een school. Onderwijssector, programma, groepssamenstelling en beschikbaarheid bepalen samen welke vervolgstappen mogelijk zijn.',
        'challenge' => 'Bij een onderwijsbezoek hangen veel keuzes samen. Niet ieder programma past bij iedere onderwijssector of bezoekdag. Ook de groepsgrootte, begeleiders en beschikbare plaatsen spelen mee. Het formulier moet die samenhang begrijpelijk maken tijdens het aanvragen.',
        'solution' => 'Het formulier toont vervolgvelden zodra de benodigde keuzes zijn gemaakt. De bezoeker stelt zo stap voor stap een aanvraag samen en krijgt uitleg bij ongeldige invoer. De server levert de programma- en validatieregels en controleert de aanvraag opnieuw bij het verzenden.',
        'features' => [
            ['title' => 'Keuzes die op elkaar aansluiten', 'text' => 'Programma’s worden gefilterd op onderwijssector en weekdag. Onderwijsniveaus en groepen bepalen mede welke keuzemodules beschikbaar zijn.'],
            ['title' => 'Inzicht in beschikbaarheid', 'text' => 'De kalender maakt onderscheid tussen beschikbare dagen, beperkte capaciteit en geblokkeerde of volgeboekte dagen. Het aantal leerlingen wordt ook aan de resterende ruimte getoetst.'],
            ['title' => 'Leerlingen en begeleiders', 'text' => 'Het formulier controleert minimum- en maximumaantallen per programma en berekent hoeveel begeleiders minimaal nodig zijn en hoeveel gratis meekunnen.'],
            ['title' => 'Conceptrooster en prijsopgave', 'text' => 'Op basis van de samenstelling kan een passend conceptrooster worden opgehaald. De prijsopgave verwerkt het programma, de aantallen en gekozen opties voor eten en drinken.'],
            ['title' => 'Afronding met controle', 'text' => 'Er is ruimte voor opmerkingen en akkoord met de voorwaarden. Voor opslag controleert de server de invoer en beschikbaarheid opnieuw.'],
            ['title' => 'Bevestiging van de aanvraag', 'text' => 'De aanvraagverwerking heeft een e-mailkoppeling voor een ontvangstbevestiging met aanvraaggegevens en beschikbare bijlagen. Een mislukte mailverzending krijgt een aparte melding nadat de aanvraag is opgeslagen.'],
        ],
        'steps' => [
            ['title' => 'Gegevens en bezoekdatum', 'text' => 'School- en contactgegevens invullen en een datum kiezen met zicht op de beschikbaarheid.'],
            ['title' => 'Programma en groep', 'text' => 'Onderwijssector, programma, niveaus, groepen en eventuele keuzemodule kiezen. Daarna volgen leerlingen en begeleiders.'],
            ['title' => 'Overzicht en extra opties', 'text' => 'Het conceptrooster bekijken, eten en drinken kiezen en de prijsopgave controleren.'],
            ['title' => 'Aanvraag afronden', 'text' => 'Opmerkingen toevoegen, de voorwaarden accepteren en de aanvraag verzenden.'],
        ],
        'scope' => 'Deze case richt zich op het publieke aanvraagformulier. De applicatie heeft daarnaast een afzonderlijke interne omgeving voor planning en beheer. Een ontvangen aanvraag staat eerst in optie; de ontvangstbevestiging is nog geen definitieve boeking.',
        'stack' => ['Vue 3', 'TypeScript', 'Vite', 'PHP', 'Flatpickr', 'PHPMailer'],
        'contactTitle' => 'Ook een aanvraagproces met veel keuzes?',
        'contactText' => 'Vertel welke gegevens, regels en vervolgstappen in jouw proces samenkomen. Dan kijken we hoe een passend formulier daarbij kan helpen.',
    ],
];
