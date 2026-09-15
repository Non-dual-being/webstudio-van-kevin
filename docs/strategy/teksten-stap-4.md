# Persoonlijke websiteteksten hersteld en afgerond: stap 4

Datum: 13–15 september 2026.

## Beginsituatie en herstel

Het projectpad is `C:\wamp64\www\kevin-webstudio`. De branch `feature/public-visual-refinement` begon met een schone werkboom, zonder staged of nieuwe bestanden, op `ae5a99a25781a71d5d44c5c52f8e2e6511e183f3`. Na `git fetch origin` waren de lokale en remote branch gelijk: nul commits voor en nul achter.

De projectinstructies in README en het lokale runbook, de positionering, ontwerpkeuzes, het verslag van stap 3, publieke pagina's en centrale projectgegevens zijn gelezen. Er is geen `AGENTS.md` aangetroffen in het project of de bovenliggende mappen. Het eerdere verslag van stap 4 is via de geschiedenis beoordeeld en na herstel opnieuw gelezen.

De volledige verschillen van de drie aangeleverde commits zijn gecontroleerd:

- `b95ca9afb75286f116e1bee162b112d8107ae890`: afgeronde merkafwerking van stap 3.
- `c45123d801740f72d0f6610d25b49a6e643363ec`: persoonlijke teksten van stap 4.
- `ae5a99a25781a71d5d44c5c52f8e2e6511e183f3`: uitsluitend het terugdraaien van stap 4, met dezelfde bestandsinhoud als `b95ca9a`.

Daarom is `git revert --no-commit ae5a99a25781a71d5d44c5c52f8e2e6511e183f3` uitgevoerd. De herstelde index was daarna exact gelijk aan `c45123d`. De geschiedenis blijft behouden. De inhoudelijke beoordeling gaf geen aanleiding voor aanvullende codewijzigingen. Dit verslag vervangt het eerdere controleverslag; de zes screenshots zijn opnieuw gemaakt op de herstelde versie.

HEAD, branch, index en hashes van alle niet-genegeerde werkboombestanden zijn tijdens de herstelopdracht bewaakt. Er zijn geen onverwachte externe wijzigingen aangetroffen.

## Resultaat en behouden vormgeving

Home beantwoordt achtereenvolgens wat Kevin kan bouwen, welk bestaand werk te bekijken is, hoe de samenwerking begint en hoe iemand zijn idee bespreekt. Vier dienstenkaarten vertrekken vanuit herkenbare vragen: een heldere website, begeleide aanvragen, overzicht in werkzaamheden en een productcatalogus.

De compacte werkwijze gebruikt de bestaande stijl van genummerde stappen: kennismaken, afspreken wat we bouwen, een eerste versie bekijken en opleveren met duidelijke afspraken.

Over mij beschrijft waarom bruikbare, begrijpelijke techniek Kevin aanspreekt. Diensten en de drie detailpagina's geven concrete voorbeelden en benoemen welke keuzes vooraf worden afgesproken. Er zijn geen opleiding, certificering, ervaringsduur, klantresultaten, prijzen, pakketlimieten, levertijden of garanties verzonnen. Er is geen portret toegevoegd.

De onderwijsformulier-uitlichting blijft een case van een bestaand project. De verduidelijkte samenvatting en introductie in `config/projects.php` blijven gedeeld door Home, Werk en de case. Bestaande functies en projectroutes zijn behouden. Er is geen interactieve demo als beschikbaar beschreven en geen demoknop toegevoegd.

Contact gebruikt een gewone native link naar `mailto:info@kevinwebstudio.nl`. Het zakelijke adres is door Kevin aangeleverd. De website toont geen formulier, verzendknop of ontvangstmelding voor nog ontbrekende functionaliteit.

Het oorspronkelijke KDS-merkbeeld, favicons, woordmerk, Home-link, headernavigatie, kleuren, lettertypen, knopcomponenten, animaties, focusstijlen, ondersteuning voor minder beweging en bestaande grids zijn behouden. Er was geen aanvullende aanpassing aan ruimte of tekstbreedte nodig. De merkafwerking van stap 3 is niet opnieuw uitgevoerd.

De titelwijziging in `resources/js/app.ts` is beoordeeld: publieke pagina's gebruiken eenmaal Kevin Webstudio. De bestaande lijst met publieke pagina's wordt hergebruikt; titelafhandeling voor overige pagina's, layouts en applicatie-initialisatie blijven functioneel gelijk.

## Definitieve homepage-intro

**Websites en webapplicaties die passen bij jouw werk.**

Ik ben Kevin. Ik bouw websites, formulieren en webapplicaties die aansluiten op de mensen die ze gebruiken. Samen kijken we wat jouw bezoekers nodig hebben en waar je werk eenvoudiger kan. Dat vertaal ik naar een heldere website of een praktisch systeem.

## Definitieve contacttekst

**Vertel me wat je wilt maken.**

Heb je een idee voor een website, een formulier of een webapplicatie? Vertel me wat je wilt bereiken, voor wie het bedoeld is en wat er nu lastig gaat.

Je hoeft de technische oplossing nog niet te kennen. Ik denk met je mee over wat nodig is en welke eerste stap past.

Je kunt me mailen op: [info@kevinwebstudio.nl](mailto:info@kevinwebstudio.nl).

Het bestaande kader vraagt aanvullend naar doel, gebruikers, huidige knelpunten en eventuele planning. De drie bestaande stappen onderaan de contactpagina zijn inhoudelijk afgestemd op de werkwijze.

## Gewijzigde bestanden tegenover de beginsituatie

| Bestand | Wijziging |
| --- | --- |
| `resources/js/pages/Home.vue` | Kop, intro, concrete klantvragen en werkwijze hersteld |
| `resources/js/pages/About.vue` | Persoonlijke tekstbasis en contactlink hersteld |
| `resources/js/pages/Contact.vue` | Uitnodigende tekst, gespreksvragen en zakelijke mailto-link hersteld |
| `resources/js/pages/Services/Index.vue` | Diensten vanuit de vraag van de bezoeker hersteld |
| `resources/js/pages/Services/Websites.vue` | Voorbeelden en afspraken over inhoud en beheer hersteld |
| `resources/js/pages/Services/Dashboards.vue` | Tekst over aanvragen en werkzaamheden hersteld |
| `resources/js/pages/Services/Webshops.vue` | Tekst over catalogus, bestellen en koppelingen hersteld |
| `resources/js/app.ts` | Merknaam in de publieke paginatitels hersteld |
| `config/projects.php` | Onderbouwde samenvatting en introductie van de bestaande case hersteld |
| `docs/brand/ontwerpkeuzes.md` | Bevestigd zakelijk adres en huidige contactopzet hersteld |
| `docs/strategy/teksten-stap-4.md` | Herstel, definitieve teksten en actuele controle-uitkomsten vastgelegd |
| `docs/strategy/screenshots/stap-4/home-1440.png` | Nieuw screenshot van de herstelde Home |
| `docs/strategy/screenshots/stap-4/home-390.png` | Nieuw mobiel screenshot van de herstelde Home |
| `docs/strategy/screenshots/stap-4/over-mij-1440.png` | Nieuw screenshot van de herstelde Over mij |
| `docs/strategy/screenshots/stap-4/over-mij-390.png` | Nieuw mobiel screenshot van de herstelde Over mij |
| `docs/strategy/screenshots/stap-4/contact-1440.png` | Nieuw screenshot van de herstelde Contact |
| `docs/strategy/screenshots/stap-4/contact-390.png` | Nieuw mobiel screenshot van de herstelde Contact |

Authenticatie, database, CI, SSR, mailconfiguratie, dependencyversies en serverinstellingen zijn ongewijzigd. Er zijn geen packages toegevoegd of globale instellingen aangepast. GeoFortForm4 is niet gewijzigd. `positionering.md` was al onderdeel van de basis en is behouden.

## Uitgevoerde controles op de definitieve code

De onderstaande controles zijn tijdens deze herstelopdracht uitgevoerd op de herstelde code. Daarna zijn uitsluitend dit verslag en de screenshots bijgewerkt. De broncode is sindsdien ongewijzigd, waardoor deze resultaten ook gelden voor de uiteindelijk vastgelegde code.

Voor de opdrachten stond `C:\wamp64\bin\php\php8.4.15` vooraan in het tijdelijke proces-PATH, ook voor Wayfinder tijdens de build. PHP 8.4.15 en de lokaal aanwezige pnpm 11.5.2 zijn gebruikt.

| Controle | Resultaat |
| --- | --- |
| `pnpm format:check` | Geslaagd |
| `pnpm lint:check` | Geslaagd |
| `pnpm types:check` | Geslaagd |
| `php vendor/bin/pint --test config/projects.php` | Geslaagd |
| `pnpm build` | Geslaagd, inclusief Wayfinder met PHP 8.4.15 |
| `php artisan test --compact tests/Feature/PublicPagesTest.php` | Pas na de geslaagde build uitgevoerd: 13 tests geslaagd, 117 assertions |
| `git diff --check` | Geslaagd |
| `git diff --cached --check` | Geslaagd |
| Browser op 1440 en 390 px | Tien publieke pagina's per breedte gecontroleerd; geen horizontale overloop of door de DOM-meting vastgestelde tekstafkapping |
| Koppen en titels | Precies één h1 en één passende, unieke documenttitel per pagina; geen overgeslagen kopniveaus |
| Links en toetsenbord | Bestaande bestemmingen en zichtbare focus via echte Tab-toetsaanslagen gecontroleerd; navigatie naar case en Contact werkt, ook met Enter |
| E-mailadres | Exacte native mailto-bestemming, toetsenbordbediening, zichtbare focus en aanraakhoogte van minimaal 44 px gecontroleerd |
| Browserfouten | Geen JavaScript-, console- of HTTP-fouten |
| Beeldweergave van nieuwe screenshots | Niet uitvoerbaar: de lokale imageviewer faalt door de Windows-sandboxfout `helper_unknown_error: setup refresh had errors` |

De tien gecontroleerde routes zijn `/`, `/werk`, `/werk/onderwijsformulier`, `/diensten`, de drie bestaande dienstendetailroutes, `/over-mij`, `/contact` en `/privacy`. In totaal zijn twintig viewportcontroles en twintig toetsenbordcontroles uitgevoerd. De browserresultaten en screenshots zijn op 13 september 2026 om 21:32 UTC vastgelegd.

De preview gebruikte de bestaande projectconfiguratie en de geslaagde productiebuild op een eigen vrije poort 61264. Chrome gebruikte een eigen tijdelijk profiel en debuggingpoort 61265. Helpers, logs, browserprofiel, extra screenshots en browsermetingen zijn buiten de projectmap bewaard, onder de tijdelijke map `kevin-webstudio-step4-restore-dfdbc752322c43b18d1885d93e3d10cf`. Er is geen tijdelijke Vite-configuratie toegevoegd.

## Screenshots en beperkingen

De zes onderstaande screenshots zijn nieuw vastgelegd op de herstelde versie. Ze zijn niet visueel goedgekeurd: daadwerkelijk openen met de beschikbare beeldweergave lukte niet. Leesbaarheid en tekstafbreking moeten daarom nog handmatig op de beelden worden beoordeeld. De geslaagde DOM-, navigatie- en focuscontroles vervangen die beoordeling niet.

| Pagina | Desktop | Mobiel |
| --- | --- | --- |
| Home | [1440 px](screenshots/stap-4/home-1440.png) | [390 px](screenshots/stap-4/home-390.png) |
| Over mij | [1440 px](screenshots/stap-4/over-mij-1440.png) | [390 px](screenshots/stap-4/over-mij-390.png) |
| Contact | [1440 px](screenshots/stap-4/contact-1440.png) | [390 px](screenshots/stap-4/contact-390.png) |

De browsercontrole gebruikt Chrome-emulatie, geen fysieke telefoon. De screenshots bevatten de publieke Home-, Over mij- en Contactpagina's in een eigen browseromgeving, zonder aangemelde gebruiker of ingevulde aanvraaggegevens.

Bij de toetsenbordcontrole is het native activeringsgedrag van de mailto-link gecontroleerd; het starten van een externe mailclient is in de test onderschept. Er is geen e-mail verstuurd en e-mailbezorging is niet getest.

De bestaande melding over optionele Fontaine-ondersteuning blokkeert de build niet. Normale lokale tools liepen tegen de genoemde Windows-sandboxfout aan; terminalcontroles konden met toegestane uitvoering buiten de sandbox worden afgerond. De imageviewer biedt die mogelijkheid niet. Dit is een omgevingsbeperking.

De eerder gemelde volledige suite met 103 tests, PHPStan en Composer-validatie is tijdens deze herstelopdracht niet opnieuw uitgevoerd en wordt hier niet als nieuwe validatie opgevoerd.

## Voor de eerste publieke release

- Beoordeel de nieuwe screenshots nog visueel op leesbaarheid en tekstafbreking bij beide breedtes.
- Bouw het echte contactformulier met zakelijke e-mail in de afzonderlijk geplande stap.
- Bouw de interactieve onderwijsformulier-demo in de afzonderlijk geplande stap.
- Bepaal prijzen, pakketgrenzen, doorlooptijden en onderhoudsafspraken in stap 5.

De huidige mailto-link en case vervangen de twee verplichte functionaliteiten niet. Merge en deployment volgen afzonderlijk.
