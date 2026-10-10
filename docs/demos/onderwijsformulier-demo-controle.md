# Onderwijsformulier-demo — stap 12

Lokaal opgeleverd op **10 oktober 2026**, vanaf `7bc95fb` op `feature/public-visual-refinement`. De goedgekeurde portfoliosite, contactverwerking, mails, SMTP en privacycode zijn behouden. Geen deployment, bronappstart of verwerking van de bestaande contactqueue.

## Bekijken en starten

**Lokale preview: http://127.0.0.1:8120**. De [startinstructies](../../demos/onderwijsformulier/README.md) staan bij de zelfstandige demo. `npm.cmd ci`, `npm.cmd run build`, `npm.cmd start` vanuit `demos/onderwijsformulier/`. WAMP PHP **8.4.15** wordt expliciet geselecteerd en gecontroleerd; Node **22.23.1** is gebruikt voor build en tests.

School- en contactgegevens zijn fictief ingevuld. De bezoeker kan datum, sector, programma, niveaus, groepen, aantallen, catering en opmerkingen wijzigen. De oorspronkelijke doorlopende formulierstructuur blijft behouden. Afsluiten geeft: **“Demo afgerond. Er is geen aanvraag opgeslagen of e-mail verstuurd.”** De resetknop blijft beschikbaar.

## Herkomst en aanpassingen

De gerichte vergelijking van stap 11 gebruikte bronrevisie **`1a43fc16bdaafb27920d8c6c819fa58c6e02dc35`** van GeoFortForm4, branch `feature/calendar-booking-clickability`. De geselecteerde bestanden hadden geen lokale bronwijzigingen. Bronrevisie en alle 124 geregistreerde SHA-256-hashes zijn na de bouw opnieuw gecontroleerd. Geen productie-.env, database, log of echte aanvraag uit de bron gelezen; daar niets gestart of gewijzigd.

Het [bronmanifest](../../demos/onderwijsformulier/source-manifest.json) noemt ieder geselecteerd bestand, de bronhash, bestemming, demohash en noodzakelijke aanpassing. 74 overgenomen bestanden zijn inhoudelijk gelijk afgezien van regeleinden en eindspaties; 43 aangepaste kopieën/extracties bevatten de demo-aansluitingen. Referentiebestanden die niet worden geladen zijn afzonderlijk herkenbaar.

- Vue-componenten, conditionele flow, normalisatie, validatie, programma-, onderwijs-, module-, groeps- en aantallenregels zijn hergebruikt. Mobiele niveaunamen worden volledig afgebroken over regels. Ongebruikte imports en enkele lifecycleproblemen zijn lokaal gecorrigeerd.
- `BookingPriceCalculator` verschilt uitsluitend door verplichte catalogusinjectie. De oorspronkelijke rekenregels in cents, begeleidersberekening en afronding zijn behouden. Eén fictieve `server/catalog.json` voedt zowel informatiepanelen als berekening; de echte standaardtarieven zijn verwijderd.
- Alleen-lezen interfaces vervangen de drie concrete SQL-afhankelijkheden. Fictieve capaciteitsaantallen en blokkades komen uit geheugen; een geïnjecteerde klok voedt kalender en datumvalidatie. De kalender blijft ook na jaarwisselingen bruikbaar.
- De bestaande roosterresolver krijgt een lege repository en geeft de bestaande unavailable-toestand met uitleg, modules en groepsaantal. Geen roosterbestanden, planner of beheeromgeving.
- De oorspronkelijke validatiesequentie tot en met capaciteit is uit `BookingFormHandler` gehaald. De daaropvolgende cooldownregistratie, aanvraagopslag, mail en queue zijn niet overgenomen.
- Alle zeven contracten lopen uitsluitend via `/demo-api/{rules,policy,programs,dates,roster,quote,complete}` op dezelfde origin. Geen originele bootstrap, Laravel, Composer-loader, productie-URL of fallback.
- Reset unmount het formulier, ruimt timers, listeners, kalender, validatieregels en caches op en breekt requests af. Een generatiecontrole weigert late antwoorden, ook als de testadapter annuleren bewust negeert.
- Eigen package/lockfile, start-, build-, type-, lint- en testcommando's. De hoofdsite sluit `demos/` uit van haar ESLint- en Pint-scope; de demo heeft eigen controles. Nieuwe dependencies zijn gericht bijgewerkt na de audit.

## Uitgevoerde controles

| Controle | Lokaal resultaat |
| --- | --- |
| Demo productiebuild | Vite 7.3.7 geslaagd; daarna pas de daarop gebaseerde browsertests |
| Typecontrole / ESLint | Geslaagd; eigen configuratie |
| PHP-syntax en domein | 36 bestanden; **64 controles** geslaagd op PHP 8.4.15 |
| Statische isolatie | 35 runtime-PHP- en 67 frontendbestanden gecontroleerd; geen opslag-, SQL-, mail-, log- of externe fetchconnector |
| Dependency-audit | `npm audit`: **0 bekende kwetsbaarheden** op controledatum |
| Browser op 390 en 1440 px | **30 controles per breedte**, beide geslaagd; geen horizontale pagina-overloop |
| Browsernetwerk en opslag | 0 externe paginarequests, 0 cookies, lege local/session storage, IndexedDB en Cache Storage; geen serviceworker of formulierdata in history state |
| Routerafscherming | Broncode, catalogusbestand, `.env`, oude API-paden en roosterdownloadpad geven 404; geen doorval naar een andere app |
| Hoofdsite frontend | Bestaande format-, lint- en typecontroles geslaagd |
| Hoofdsite regressies | **198 tests, 1332 assertions** geslaagd; geïsoleerde SQLite in geheugen, arraymail en aparte cache-/queueconfiguratie |
| Hoofdsite PHP | Pint, PHPStan en Composer-validatie geslaagd |

Getest zijn woensdag/ochtend versus andere dagen, sector- en niveauwissels, ongeldige/uitgesloten modules, 39/40 en 80/160/max+1 leerlingen, te weinig/50/51 begeleiders, beperkte en volle capaciteit, ongeldige datums, opmerkingen van 600/601 Unicode-tekens, ontbrekend akkoord, cateringgrenzen, prijs- en BTW-afronding. De browserflow gebruikt Tab, pijltjestoetsen, Enter en spatie voor selectie en afronding. Ook een initiële API-fout, een prijsnetwerkfout met herstel en reset tijdens kalender-, prijs- en afrondingsrequests zijn bewezen.

De hoofdsitetests bevatten negatieve transportscenario's. De suitebrede schedulertest logt bovendien een mislukt `contact:mail --dispatch`-subproces in de geïsoleerde in-memory omgeving; PHPUnit eindigt geslaagd. Dit is geen nieuwe productie-workercontrole en heeft de bestaande ontwikkelqueue niet verwerkt. De demo zelf gebruikt deze scheduler niet.

## Beeldbewijs

De screenshots zijn **daadwerkelijk geopend en bekeken**: introductie, toetsenbordfocus, kalender, validatiefout, niveauwissel, prijsopgave, lange opmerkingen, API-fout en succes op beide breedtes. Daarbij is de mobiele afkapping van niveaunamen hersteld en opnieuw gecontroleerd. Geen bronappscreenshot of echte aanvraag is gebruikt.

Een compacte selectie en de volledige controle-uitkomst staan in [screenshots/onderwijsformulier-demo](screenshots/onderwijsformulier-demo/). Alle screenshots zijn opnieuw te genereren met `npm.cmd run test:browser`; de overige beelden staan lokaal in de genegeerde `demos/onderwijsformulier/artifacts/`-map. Browserprofielen zijn tijdelijke testprofielen. De eigen testserver en testbrowser zijn na elke controle gesloten; de preview op 8120 staat los daarvan.

## Grenzen en voorwaarden voor publicatie

1. **Nog geen publieke URL of deployment.** Kies een aparte demo-origin met PHP 8.4.15 en HTTPS; configureer alleen de toegestane router/buildbestanden. Gebruik de ingebouwde previewserver niet als publieke webserver. Controleer daar opnieuw netwerkisolatie, headers, geen echte connectors en geen formulierinhoud in webserver-, proxy- of foutlogs. Pas daarna kan een portfoliolink worden toegevoegd.
2. **Publicatierechten en presentatie bevestigen.** De gebruikte broncode is op opdracht hergebruikt; klantassets en echte tarieven zijn weggelaten. De bevoegdheid om deze code/case publiek te publiceren wordt hier niet als juridisch bewezen aangemerkt.
3. **Beperkte browserscope.** De lokale controles zijn uitgevoerd in Chrome, met mobiele viewportemulatie en toetsenbord. Er is nog geen fysieke iOS/Android- of volledige schermlezertest. Beschikbaarheid, tarieven en vakantieperioden blijven bewust fictief; roosterdownloads en planner vallen buiten deze versie.

Er is geen bewijs toegevoegd over echte ontvangst van contactmails, Reply-To of SPF/DKIM/DMARC. De bestaande productievoorwaarden in de contact-, zakelijke-mail- en privacyrunbooks blijven van kracht.
