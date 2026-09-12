# Demo-voorstel: Onderwijsformulier

Status: brononderzoek en portfolio-case afgerond in deze run; de interactieve demo is nog niet gebouwd.

## Afbakening en bewijs

Onderzocht: `C:/wamp64/www/GeoFortForm4`, uitsluitend door bronbestanden te lezen. Het toepasselijke `AGENTS.md` is gelezen. Geen productie-.env, databasebestanden, logs of echte aanvragen gelezen; geen applicatie, migraties of tests in dat project gestart. De genoemde functies zijn vastgesteld in code, niet door echte boekingen te versturen. Exacte persoonlijke bijdrage, klanttoestemming, gebruikscijfers en tijdsbesparing zijn niet vastgesteld en worden niet geclaimd.

De eerste demo betreft alleen het **publieke aanvraagformulier**. De interne planner is een afzonderlijke toepassing, met eigen Vue-ingang, hashrouter, private sessie en beheer-API's. Planning, statuswijzigingen, rapportages, exports, login en beheer worden niet in de eerste demo opgenomen.

Er zijn geen geschikte, aantoonbaar geschoonde screenshots vastgesteld. Daarom heeft de portfolio-case een tekstuele opzet met een procesoverzicht. Bestaande roosterafbeeldingen, PDF's, logo's en huisstijlbestanden van de bronapp worden niet gepubliceerd.

## Stack en bronkaart

Versies hieronder zijn de vereisten in de onderzochte pakketdefinities; geen claim over de geïnstalleerde productieversies.

| Onderdeel                | Gevonden stack / relevante bestanden                                                                                                                                                                                                                                        |
| ------------------------ | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Publieke frontend        | Vue `^3.5.24`, TypeScript `~5.9.3`, Vite `^7.2.4`, Flatpickr `^4.6.13`, lucide-vue-next. `package.json`, `vite.config.ts`.                                                                                                                                                  |
| PHP                      | Eigen PHP-applicatie, geen Laravel. PHP `>=8.1`, Composer PSR-4 `GeoFort\\` naar `src/`, phpdotenv `^5.6`, PHPMailer `^7.0`. `composer.json`. PDO/SQL-repositories in `src/Services/Sql/`.                                                                                  |
| Publieke ingang          | `public/index.php` → `src/Controllers/indexController.php` → `templates/app.php`; `resources/js/booking/main.ts` mount `booking/App.vue`.                                                                                                                                   |
| Schermwissels            | `booking/App.vue` wisselt lokaal tussen `BookingGeoFormView.vue`, `BookingSuccessView.vue` en `BookingErrorView.vue`. Het publieke formulier gebruikt hiervoor geen Vue Router.                                                                                             |
| Formulier                | `resources/js/views/BookingGeoFormView.vue`, `resources/js/components/form/GeoForm*.vue`, `resources/js/config/booking/`, `resources/js/config/validation/`. Dit is één voortschrijdend formulier met conditionele velden, geen reeks losse routepagina's.                  |
| Herbruikbare composables | `useEducationProgram.ts`, `useEducationSelection.ts`, `useEducationModules.ts`, `useStudentCount.ts`, `useSupervisorCount.ts`, `useFoodAndDrinkSelection.ts`, `useBookingRoster.ts`, `useBookingPriceQuote.ts`, `useFormSubmit.ts`.                                         |
| Regels                   | `src/Booking/BookingPolicy.php`, `BookingProgramConfig.php`; `src/Validation/FormRules.php`, `Validator.php`, de validators voor programma, onderwijsselectie, module, leerlingen, begeleiders, catering en voorwaarden.                                                    |
| Kalender                 | `GeoFormBookingDateField.vue`, `config/booking/calendar/helpers.ts`, `shared/disabledDatePresentation.ts`, `types/booking/BookingDateType.ts`; `src/Services/Booking/Availability/BookingAvailabilityService.php`, `src/Services/Http/Api/Booking/DisabledDatesAction.php`. |
| Rooster                  | `src/Services/Booking/Roster/BookingRosterResolver.php`, `RosterGroupCountResolver.php`, `src/Services/Sql/RosterSqlService.php`, `GeoFormRosterPreview.vue`.                                                                                                               |
| Prijs                    | `src/Services/Booking/Pricing/BookingPriceCalculator.php`, `BookingPriceCatalogRegistry.php`, `BookingPriceCatalog.php`, `BookingPriceQuote.php`; `GeoFormPriceQuotePreview.vue`.                                                                                           |
| Verzending               | `public/booking/validatie.php`, `src/Services/Http/Api/Booking/BookingFormHandler.php`, `src/Services/Booking/Data/BookingRequestData.php`, `src/Services/Booking/Submission/BookingSubmissionService.php`.                                                                 |
| Mail                     | `src/Services/Mail/BookingMailService.php`, `MailInterface.php`, `PhpMailerMailer.php`, `Templates/BookingRequestMailTemplate.php`, rooster- en documentbijlageresolvers.                                                                                                   |
| Beheer, buiten scope     | `resources/js/admin.ts`, `resources/js/admin/router/index.ts` met `createWebHashHistory`, `public/api/admin/`, `src/Services/Dashboard/`. Vue Router `^5.1.0` en Chart.js `^4.5.1` staan in hetzelfde package.json.                                                         |
| Kevin Webstudio          | Laravel `^13.17`, Inertia 3, Vue 3, TypeScript, Vite 8, Tailwind 4, Wayfinder. `package.json`, `composer.json`, `vite.config.ts`. PHP 8.4.15 gebruikt voor de controle; de Composer-ondergrens alleen is niet voldoende om de geïnstalleerde tooling te kiezen.             |

Alle bronpaden in deze tabel zijn relatief aan GeoFortForm4, behalve de rij Kevin Webstudio.

## Wat werkelijk bestaat en hergebruikt kan worden

1. **School- en contactgegevens.** De generieke velden en frontendvalidatie kunnen worden hergebruikt met de backendregels uit FormRules. Er zijn landafhankelijke postcode- en telefoonregels. Vul fictieve gegevens in; gebruik geen voorbeeld uit een aanvraag.
2. **Datum en onderwijssector.** De kalender verwerkt beschikbare dagen, beperkte capaciteit, volle dagen en blokkades, met uitleg. Sector en datum sturen de programmaselectie. Hergebruik de kalendercomponent en de bijbehorende datumhelpers.
3. **Programma, niveaus, groepen en modules.** De configuratie is leidend voor het filteren. Het ochtendprogramma geldt voor primair onderwijs op woensdag; het dagprogramma voor de drie geconfigureerde sectoren op werkdagen. Behoud de bestaande selectorlogica, composables en afhandeling van gewijzigde eerdere keuzes.
4. **Leerlingen en begeleiders.** De onderzochte configuratie hanteert minimaal 40 leerlingen, maximaal 80 voor ochtend en 160 voor dag, verder beperkt door dagcapaciteit. Minimaal één begeleider per 16 leerlingen, naar boven afgerond; één gratis begeleider per 8 leerlingen; maximaal 50 begeleiders. Dit zijn bronwaarden, geen in de demo opnieuw te coderen formules.
5. **Onderwijsregels.** Primair: één niveau met één tot drie groepen. Voortgezet: één tot drie niveaus, met één tot drie groepen per niveau. Modulefilters kunnen niveaus of specifieke groepen uitsluiten. Neem deze relaties rechtstreeks over uit BookingProgramConfig, EducationSelectionValidator en ChoiceModuleSelectionValidator.
6. **Conceptrooster.** De PHP-resolver bepaalt onder andere de groepsindeling via bestaande aantallenreeksen en zoekt daarna een passend rooster in SQL. Het is geen algemene roostergenerator. De frontend heeft al een toestand voor een ontbrekend voorbeeldrooster. Gebruik die zolang er geen geschoonde demo-roosterdocumenten zijn.
7. **Catering en prijsopgave.** Keuzes en aantallen voor snacks, drinken en lunch, gevolgd door een serverberekende prijsopgave. Hergebruik de calculator inclusief berekening in centen, gratis/betaalde begeleiders en btw-afronding. Injecteer één fictieve prijscatalogus via de bestaande constructor van BookingPriceCatalogRegistry; gebruik dezelfde catalogus voor informatiepanelen en prijsopgave. BookingProgramConfig maakt nu zelf een catalogus aan: maak dit in de demokopie injecteerbaar zodat echte standaardtarieven niet terugvallen.
8. **Opmerkingen, voorwaarden en verzending.** Opmerkingen zijn optioneel en maximaal 600 tekens; akkoord is vereist. De server valideert alle keuzes opnieuw. De bestaande verzendstatussen zijn idle, pending, slow, error en success.
9. **Opslag en mail zijn afzonderlijke uitkomsten.** De submissionservice opent een transactie, vergrendelt de datum, controleert capaciteit opnieuw, slaat aanvraag, onderwijsselectie en prijsversie op, en commit. Daarna volgt mail; een mailfout wist de opgeslagen aanvraag niet. De frontend ontvangt `mailDelivery: "sent" | "failed"`. Nieuwe aanvragen staan in optie, niet definitief.

De publieke beschikbaarheid telt volgens BookingPolicy uitsluitend definitieve boekingen mee: maximaal twee scholen en 160 leerlingen per dag. Weekenden en verstreken dagen zijn niet boekbaar; het bereik loopt tot eind van het jaar twee jaar vooruit. Handmatige en vakantieblokkades komen uit disabled-datesgegevens. Een demo-aanvraag mag daarom niet stilzwijgend als definitieve reservering van capaciteit worden gepresenteerd.

## API-koppelingen vervangen

De leesclients gebruiken `resources/js/services/http/apiClient.ts` met `{ ok, data }`. Er staan zowel absolute als relatieve URL's in de code. Alleen een Vite-basepad instellen is daarom onvoldoende.

| Bestaande koppeling                                                       | Vervanging in de demo                                                                                                                                                           |
| ------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| GET `/api/getFormValidationRules.php`                                     | Zelfde DTO, rechtstreeks afgeleid uit de overgenomen PHP FormRules. Geen handgeschreven TypeScript-regelcatalogus.                                                              |
| GET `/api/getBookingConfigValues.php`                                     | Zelfde DTO uit de overgenomen BookingPolicy.                                                                                                                                    |
| GET `/api/getBookingProgramConfig.php`                                    | Zelfde structuur uit BookingProgramConfig; labels, informatieteksten, links en tarieven geschoond via één presentatieconfiguratie. Sleutels en relaties blijven gelijk.         |
| GET `api/getDisabledDates.php`                                            | Demo-endpoint met uitsluitend fictieve kalenderinput, doorgerekend door de hergebruikte beschikbaarheidslogica.                                                                 |
| POST `/booking/getBookingRoster.php`                                      | Bestaande selectieregels/resolver met een fixture-repository. Zonder goedgekeurde bestanden: bestaande unavailable-response met neutrale uitleg. Geen productierooster ophalen. |
| POST `/booking/getBookingPriceQuote.php`                                  | Hergebruikte PHP-calculator, gevoed door één expliciet fictieve catalogus. Geen opslag van prijsversies.                                                                        |
| POST `./booking/validatie.php`                                            | Nieuw demo-submitendpoint met bestaande veld- en selectieregels, maar zonder BookingSubmissionService, SQL-opslag of mail. Geeft een expliciete demo-bevestiging.               |
| Voorwaarden, roosterafbeeldingen/PDF's, footerlinks, mailto en merkassets | Eigen neutrale demo-inhoud of de bestaande unavailable-weergave. Geen proxy naar de bronapp, geen originele bijlagen of mailadressen.                                           |

Maak alle clients afhankelijk van één expliciete demo-API-configuratie. Vervang ook de rechtstreeks hardgecodeerde submit-URL in useFormSubmit. Geen productie-URL als fallback; ontbrekende configuratie moet de demo stoppen.

## Hergebruik zonder bedrijfsregels na te bouwen

Maak in een volgende run een **gecontroleerde bronselectie** in een aparte demomap. Leg per geselecteerd bestand bronpad, bronrevisie, SHA-256 en noodzakelijke demo-aanpassingen vast. Gebruik een expliciete lijst met toegestane bronbestanden; kopieer geen gehele projectboom. De bronapp blijft onaangeroerd. Controleer bij updates via bestandsvergelijking welke wijzigingen uit de bron moeten worden overgenomen.

Kopieer de bestaande Vue-componenten, composables, types en PHP-regelklassen als samenhangende eenheid. Pas presentatie en koppelingen aan, geen rekenregels. Er is nu geen kant-en-klaar gedeeld domeinpakket. Een later gedeeld pakket is mogelijk, maar wijziging van GeoFortForm4 daarvoor valt buiten deze opdracht.

Er zijn concrete technische grenzen:

- BookingAvailabilityService is final en accepteert concrete BookingCalendarSqlService en DisabledDatesSqlService. Een willekeurig object met dezelfde methoden kan dus niet direct worden geïnjecteerd. Maak **in de demokopie** kleine leesinterfaces voor de gebruikte methoden; laat de bestaande berekening intact en implementeer die interfaces met fictieve arrays. Maak de datumbron injecteerbaar voor reproduceerbare kalendercontroles.
- BookingRosterResolver is eveneens gekoppeld aan RosterSqlService. Gebruik in de demokopie een leesinterface voor de roosterlookup. Behoud groepsaantallen en selectieregels; gebruik een lege fixture-repository voor de eerste release.
- BookingFormHandler combineert validatie met submission, rate-limitregistratie en infrastructuur. Haal in de demokopie de bestaande validatiesequentie naar een herbruikbare validator die dezelfde fieldErrors teruggeeft. Laat de demo-controller die validator aanroepen. Kopieer geen SQL-transactie of mailconstructie mee.
- BookingPriceCalculator ondersteunt al injectie van BookingPriceCatalogRegistry. Gebruik die mogelijkheid; voer geen los JS-prijsmodel in.
- Neem de bestaande regeltests mee waar ze zuiver op broncode en fictieve input werken. Relevante startpunten: `scripts/tests/BookingProgramDomainTest.php`, `BookingPriceFoundationDomainTest.php`, `BookingSubmissionMailFailureContractTest.php`. Inspecteer hun dependencies eerst. Start geen MariaDB-integratietests van de bronapp.

Dit voorkomt twee handmatig onderhouden regelimplementaties. Een geversioneerde kopie vraagt wel een expliciete synchronisatiestap bij bronwijzigingen; dat is onderdeel van de volgende opdracht.

## Fictieve kalender en voorbeeldgegevens

Gebruik een scenario met een vaste seed en een Amsterdamse datumbron. Bij de start wordt een ankerdatum bepaald; tests injecteren een vaste ankerdatum. Genereer alleen kalenderinput en laat de bestaande kalenderregels bereik, weekends en capaciteitsuitkomsten bepalen.

Voorbeelden binnen het geldige bereik, gekozen op toekomstige werkdagen:

| Scenario                | Fictieve invoer                                                                      |
| ----------------------- | ------------------------------------------------------------------------------------ |
| Vrije dag               | 0 definitieve scholen, 0 leerlingen                                                  |
| Beperkte ruimte         | 1 definitieve school, 120 leerlingen; 40 plaatsen over                               |
| Vol door schoollimiet   | 2 definitieve scholen, 80 leerlingen                                                 |
| Vol door leerlinglimiet | 1 definitieve school, 160 leerlingen                                                 |
| Gesloten                | Een handmatige demoblokkade en een fictieve vakantieperiode, zichtbaar als voorbeeld |

De API retourneert de bestaande `minDate`, `maxDate`, `disabledDates`, `details`, `availabilityDetails` en `capacity`. Leid uitkomsten af uit dezelfde beleidswaarden; leg geen tweede reeks maxima in de fixturegenerator vast. Toon duidelijk dat data en beschikbaarheid fictief zijn. Een voltooide demo wijzigt geen gedeelde capaciteit.

Voorbeeldprofiel: schoolnaam **Voorbeeldschool**, contactpersoon **Demo Bezoeker**, e-mail **bezoeker@example.invalid**, Nederland, synthetische adres- en telefoonvelden die met FormRules worden gecontroleerd. Alle waarden worden nieuw gemaakt, nooit geanonimiseerd uit echte aanvragen. Laat de bezoeker starten met ingevulde voorbeeldgegevens en keuzes wijzigen. De interface vraagt expliciet om fictieve gegevens.

## Simulatie van bevestiging en reset

Na een geldige submit valideert de demo-PHP-laag de aanvraag en retourneert bijvoorbeeld `{ ok: true, mode: "demo", mailDelivery: "simulated" }`. Pas de response-union, App.vue en het bevestigingsscherm in de demokopie samen aan. Gebruik geen `sent`-status om simulatie te verhullen.

Bevestigingstekst: **“Demo afgerond. Er is geen aanvraag opgeslagen of e-mail verstuurd.”** Een overzicht van de gemaakte keuzes mag uitsluitend in het browsergeheugen blijven. Het is geen definitieve boeking en levert geen echt aanvraagnummer op. Toon desgewenst één vaste, gemarkeerde voorbeeldmail op het scherm; geen echte mailtemplate met klantlinks laden. Netwerk- en validatiefouten blijven gewone fouttoestanden; een “mail mislukt”-scenario is optioneel en expliciet fictief.

Voeg altijd een zichtbare knop **“Demo opnieuw starten”** toe, zowel bij het formulier als op de bevestiging. Die:

1. Breekt lopende fetches af en maakt late responses ongeldig via een nieuwe sessiegeneratie.
2. Unmount en mount het formulier opnieuw, zodat kalender, keuzes, foutmeldingen, submitstatus, prijsopgave en roosterstate verdwijnen.
3. Zet het voorbeeldprofiel en de oorspronkelijke scenario-seed terug; verwijdert eventuele demo-API-caches.
4. Zet focus op de formulierintroductie en scrollt naar het begin.

De huidige new-booking-actie in booking/App.vue is een bruikbaar begin, maar reset niet automatisch alle modulebrede API-caches. Gebruik geen localStorage, IndexedDB of database voor ingevulde gegevens. Vernieuwen begint opnieuw.

## Uitsluiten van productiegegevens en echte mail

De demo krijgt een eigen bootstrap die alleen geselecteerde regelklassen laadt. Laad nooit GeoFortForm4/bootstrap.php, diens Composer-bootstrap met aanvullende applicatiestart, .env-bestanden of bestaande public-entrypoints. Geen databaseconnector, migraties, SQL-repositories, aanvraagopslag, echte SMTP-configuratie, PHPMailer-transport, verzendqueue of submitlogs opnemen.

De geselecteerde regelklassen krijgen alleen fictieve repositories en configuratie. Publiceer uitsluitend expliciet toegestane demo-bestanden; controleer output op originele domeinen, mailadressen, logo's, PDF's en externe asset-URL's. Ook hardcoded contact- en voorwaardenlinks in componenten vallen onder die controle.

De browser mag alleen dezelfde demo-origin aanspreken. Een passende CSP met `connect-src 'self'`, lokaal beschikbare assets en een server zonder uitgaande mail-/databaseverbindingen ondersteunen die scheiding. Geen analytics op veldinhoud, geen request-bodylogging en geen opslag van ingevulde gegevens. De eigen demo-endpoints sturen `Cache-Control: no-store` voor submit en persoonsvelden. Controleer in browsertests dat er geen aanvragen naar andere origins vertrekken.

## Vergelijking en advies

|              | A. Binnen Kevin Webstudio                                                                                                                 | B. Afzonderlijke demo-app                                                                                                        |
| ------------ | ----------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| Frontend     | Vue kan worden ingesloten, maar de bestaande volledige pagina, globale CSS en Flatpickr moeten worden gescheiden van Inertia en Tailwind. | Bestaande Vue-mount, composables, CSS en kalender kunnen als samenhangende app worden overgenomen en geschoond.                  |
| Backend      | Laravel-routes/adapters moeten de eigen PHP-contracten aanbieden; bootstraps mogen niet worden vermengd.                                  | Kleine eigen PHP-bootstrap naast de bestaande PHP-regelklassen past directer.                                                    |
| Dependencies | Flatpickr en de gebruikte iconen ontbreken in deze vorm in Webstudio; directe integratie vraagt extra pakketbeslissingen of aanpassingen. | Bestaande noodzakelijke frontenddependencies blijven bij de demo; adminrouter en Chart.js zijn niet nodig voor de publieke flow. |
| Isolatie     | Vereist zorgvuldige scheiding van sessies, styling, routes en beveiligingsconfiguratie binnen de portfoliosite.                           | Eigen origin, assets en API; geen gedeelde sessie of toegang tot de portfolio- of productiebackend nodig.                        |
| Onderhoud    | Eén website, maar twee applicatiepatronen in dezelfde codebase.                                                                           | Extra build/runtime te beheren; bronselectie en synchronisatie blijven overzichtelijk afgebakend.                                |

**Advies: B**, een afzonderlijke Vue/Vite-demo met een kleine PHP-laag en geselecteerde bestaande domeinregels. De werkelijke architectuur maakt dit gerichter dan de volledige flow naar Inertia overzetten. Een volledig statische mock zou de serverregels en prijsberekening slechts nabootsen; dat is niet het advies. Voeg pas een link vanaf de portfolio-case toe als deze demo gebouwd, gecontroleerd en bereikbaar is. Deze run bevat geen demo-URL of demo-knop.

## Concrete volgende implementatieopdracht

Bouw in een nieuwe, geïsoleerde map binnen een expliciet toegestane workspace de eerste versie van de afzonderlijke **Onderwijsformulier-demo**, zonder GeoFortForm4 te wijzigen.

- Selecteer en documenteer de hierboven genoemde publieke bronmodules met revisie en hashes; geen beheeromgeving, data, originele assets of originele bootstrap kopiëren.
- Behoud de huidige conditionele flow tot en met opmerkingen/voorwaarden en gesimuleerde bevestiging. Geen nieuwe wizardindeling ontwerpen.
- Maak uitsluitend de benodigde leesinterfaces, een injecteerbare klok, fictieve kalenderrepositories en één fictieve prijscatalogus. Gebruik de bestaande veld-, onderwijs-, programma-, module-, aantallen- en prijsregels.
- Bied de zeven genoemde API-contracten aan binnen de demo. Rooster gebruikt de bestaande unavailable-toestand; geen roosterdownloads, mailweergave of beheerfuncties in versie één. Voorwaarden krijgen een neutrale lokale demotekst.
- Voeg voorbeeldprofiel, duidelijke demo-uitleg en volledige reset toe. Geen opslag of echte mail.
- Controleer positieve en negatieve paden: woensdag/ochtend versus andere dag, sectorwissel, ongeldige module na niveauwissel, 39/40 en programma-maxima, beperkte dagcapaciteit, te weinig begeleiders, opmerkingen op 600/601 tekens, ontbrekend akkoord, prijsafronding, API-fout en reset tijdens een lopend request. Test de bestaande regels met synthetische input; niet alleen de happy path.
- Verifieer netwerkisolatie, afwezigheid van data-/SMTP-connectors, neutrale teksten/links en desktop/mobiel. Lever bronmanifest, wijzigingen, checks en screenshots op.
- Nog geen publicatie/deployment. Een echte link in Webstudio volgt pas zodra een gecontroleerde demo op een afgesproken URL bestaat.

## Opgeleverde portfolio-teksten

De volledige gebruikte teksten staan centraal in [config/projects.php](../../config/projects.php) en worden als Inertia-projectgegevens gedeeld door Home, Werk en de case. De case staat op `GET /werk/onderwijsformulier` (`Work/Show`).

- Titel: **Onderwijsformulier**
- Categorie: **Formulieren en boekingssystemen**
- Kaarttekst: “Een onderwijsbezoek aanvragen, van een passende datum en programma tot een prijsopgave en bevestiging van de aanvraag.”
- Inleiding: “Een publiek aanvraagformulier dat meebeweegt met de keuzes van een school. Onderwijssector, programma, groepssamenstelling en beschikbaarheid bepalen samen welke vervolgstappen mogelijk zijn.”
- Case-onderdelen: De uitdaging; De oplossing; Van bezoekidee naar aanvraag; Belangrijke functies; Achter het formulier.
- Contacttitel: “Ook een aanvraagproces met veel keuzes?”
- Contactactie: **Neem contact op** → `/contact`, met de bestaande PublicButtonLink.

De bestaande Contact-pagina heeft nog geen bevestigd zakelijk e-mailadres of verzendformulier, zoals vastgelegd in docs/brand/ontwerpkeuzes.md. De case verwijst naar die pagina; er is geen adres verzonnen. De case bevat geen persoonlijke taakverdeling, klantquote, klantlogo, meetbaar resultaat of claim over tijdsbesparing.
