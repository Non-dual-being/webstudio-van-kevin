# Onderwijsdashboarddemo — run 12B

Opgeleverd op 18 oktober 2026, vanaf `29c1ea0` op `feature/public-visual-refinement`. De gewone Webstudio-site, zakelijke mail, contactqueue, SMTP en privacycode zijn niet gewijzigd. Geen deployment, bronappstart of echte verzending.

## Lokaal bekijken

- Formulier: **http://127.0.0.1:8120/#/formulier**
- Dashboard: **http://127.0.0.1:8120/#/aanvragen**

Gebruik de navigatie binnen hetzelfde tabblad. [Startcommando’s en opslaggedrag](../../demos/onderwijsformulier/README.md) staan bij de demo. PHP **8.4.15**, Node **22.23.1** en de bestaande Vue/Vite-tooling; geen dependencies toegevoegd.

Probeer vijf stappen: kies een vrije woensdag en een ochtendprogramma met fictieve gegevens; rond af; open **Bekijk in het demodashboard**; wijzig naar 48 leerlingen en 6 begeleiders en sla op; maak definitief en bekijk daarna het overzicht. Reset herstelt de voorbeelden.

## Werkende beheerstappen

De lijst heeft schoolzoekfunctie, status-, programma- en bezoekdatumfilters, datumsortering en pagina’s van 20. Totalen volgen de actuele selectie. Een detail toont onderwijskeuzes, groepen, aantallen, catering, voorbeeldprijs en status. School/contactgegevens, leerlingen, begeleiders, catering en opmerkingen zijn bewerkbaar. PHP valideert vóór het vervangen van een record en berekent de prijs opnieuw. Ongeldige invoer veroorzaakt geen gedeeltelijke wijziging.

Nieuwe formulieraanvragen verschijnen één keer als **In optie**, met een link naar het juiste detail. De echte bronstatussen **In optie**, **Definitief** en **Afgewezen** en hun overgangspolicy zijn behouden. Definitief maken controleert inhoud, datum, begeleiding en capaciteit. Alleen definitieve records tellen mee in de formulierkalender. De demonstratierol mag geen regels overschrijven. Beheeraantallen vanaf 1 zijn apart behandeld; een publieke aanvraag en bevestiging hebben aanvullende eisen. Bij niet-definitieve aanvragen kunnen bronconforme waarschuwingen blijven staan.

## Bron en dataset

[Formuliermanifest](../../demos/onderwijsformulier/source-manifest.json) en [dashboardmanifest](../../demos/onderwijsformulier/dashboard-source-manifest.json) registreren bron- en demohashes. **168 bronbestanden** zijn opnieuw geverifieerd op revisie `1a43fc16bdaafb27920d8c6c819fa58c6e02dc35`; **32 extra bestanden** zijn geselecteerd voor hergebruik. De alleen gelezen bronrouter heeft lokale planner-routes; die afwijking is vastgelegd en niet overgenomen. De gekopieerde regels hebben geen lokale bronwijzigingen. Geen productiegegevens, .env, database, exports, mailboxen of logs gelezen.

Seed **120100** genereert exact 100 unieke fictieve scholen en 100 aanvragen. De injecteerbare klok gebruikt **Europe/Amsterdam**; tests gebruiken **2031-10-06** en jaarwisselingen. Sectoren, programma’s, groepen, modules, catering, aantallen en prijzen volgen de geselecteerde bronregels en bestaande fictieve catalogus. Er zijn historische en toekomstige bezoeken, vrije en beperkte dagen en alle bronstatussen. Alleen synthetische contactgegevens en `example.invalid`-adressen; geen leerlinggegevens of actieve contactlinks.

## Opslag en isolatie

Eén Vue-state per tabblad bevat alle actuele records. PHP verwerkt invoer en compacte capaciteitssamenvattingen zonder gedeelde veranderbare opslag. Refresh en reset herstellen 100 voorbeelden; maximaal 20 eigen aanvragen extra. Geen cookies, permanente browseropslag, serversessies, database, mail, queue of productie-API. Requests zijn begrensd op 32 KiB. Reset ruimt invoer, filters, caches en fouten op; abort/generatiecontroles weigeren late responses. Verdwenen detailroutes tonen uitleg.

## Gerichte controle

| Controle | Resultaat |
| --- | --- |
| Typecontrole, ESLint, PHP-syntax, domein/integratie, isolatie en build | Geslaagd; 608 domeincontroles, 67 PHP-bestanden op syntax |
| Bestaande formulierflow, 390 / 1440 px | 30 controles per breedte |
| Dashboard en gekoppelde flow, 390 / 1440 px | 31 controles per breedte |
| Twee afzonderlijke browsercontexten | Wijzigingen en reset van A laten B intact |
| Reset tijdens aanvragen / refresh / oude detailroute | Geen dataterugkeer of crash |
| Netwerk, browseropslag en routerafscherming | Geen externe requests, cookies of permanente opslag; bron/manifest/oude API’s niet bereikbaar |
| Hoofdsite | Geen integratiewijzigingen; volledige eerdere audit niet herhaald |

De [zes screenshots](screenshots/onderwijsdashboard-demo/) zijn daadwerkelijk geopend: lijsten en bewerken met fout op beide breedtes, desktopdetail en mobiel detail van de gekoppelde eigen aanvraag. Toetsenbordfocus en smalle layout zijn gecontroleerd. De [testresultaten](onderwijsdashboard-testresultaten.json) bevatten de uitgevoerde browserchecks. Lokaal bewezen gedrag is geen claim over productie-isolatie.

## Vervolg

Programma, datum en onderwijsselectie zijn in beheer alleen leesbaar. De **volledige roosterplanner en personeelsplanning** blijven afzonderlijk vervolgwerk, evenals accounts, exports, facturen en echte verzending. Er is geen financiële snapshotadministratie; getoonde prijzen zijn actuele voorbeelden. Publicatierechten, hosting/HTTPS/afscherming en logbeleid, privacy en echte mailontvangst/Reply-To/SPF/DKIM/DMARC blijven voorwaarden voor stap 13A. Geen fysieke mobiele apparaten of volledige schermlezeraudit getest.
