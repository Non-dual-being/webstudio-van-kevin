# Positionering toegepast — stap 4

Datum: 13 september 2026. Uitgangspunt: de schone branch `feature/public-visual-refinement` op `b95ca9a`, gelijk aan de opgehaalde remote. De README, het lokale runbook, positionering, ontwerpkeuzes, het rapport van stap 3, publieke pagina’s en centrale projectgegevens zijn vooraf gelezen. Er is geen `AGENTS.md` aangetroffen in het project of de bovenliggende mappen.

## Resultaat

Home beantwoordt achtereenvolgens wat Kevin kan bouwen, welk werk te bekijken is, hoe de samenwerking verloopt en hoe iemand zijn idee bespreekt. De bestaande dienstenkaarten vertrekken vanuit een heldere website, begeleide aanvragen, overzicht in werkzaamheden en een productcatalogus. De nieuwe compacte werkwijze gebruikt de bestaande stijl van genummerde stappen: kennismaken, afspreken wat we bouwen, een eerste versie bekijken en opleveren met duidelijke afspraken.

Over mij beschrijft waarom bruikbare, begrijpelijke techniek Kevin aanspreekt. Diensten en de drie detailpagina’s geven concrete voorbeelden en benoemen welke keuzes vooraf worden afgesproken. Er zijn geen biografische gegevens, klantresultaten, prijzen, pakketlimieten, levertijden of garanties toegevoegd. Het aanbod beschrijft mogelijke opdrachten; het suggereert geen onbewezen klantenbestand.

De onderwijsformulier-uitlichting blijft een case van een bestaand project. Samenvatting en introductie zijn in `config/projects.php` verduidelijkt en blijven gedeeld door Home, Werk en de case. De bestaande functies en projectroutes zijn behouden. Er wordt geen interactieve demo aangeboden of als beschikbaar beschreven.

Contact toont een gewone native link naar `mailto:info@kevinwebstudio.nl`, met de bestaande publieke kleuren, onderstreping en toetsenbordfocus. Het zakelijke adres is in deze opdracht door Kevin bevestigd. De website toont geen formulier, verzendknop of ontvangstmelding voor nog ontbrekende functionaliteit.

Logo, favicon, kleuren, fonts, headernavigatie, knopcomponenten, knopanimaties en bestaande grids zijn behouden. De huidige breedtes waren ook geschikt voor de nieuwe teksten. Alleen de nieuwe werkwijze en aanvullende contactlinks voegen pagina-elementen toe. Publieke paginatitels gebruiken nu Kevin Webstudio als merknaam, via de bestaande lijst met publieke pagina’s; de eerdere titelafhandeling voor overige pagina’s is behouden.

## Definitieve homepage-intro

**Websites en webapplicaties die passen bij jouw werk.**

Ik ben Kevin. Ik bouw websites, formulieren en webapplicaties die aansluiten op de mensen die ze gebruiken. Samen kijken we wat jouw bezoekers nodig hebben en waar je werk eenvoudiger kan. Dat vertaal ik naar een heldere website of een praktisch systeem.

## Definitieve contacttekst

**Vertel me wat je wilt maken.**

Heb je een idee voor een website, een formulier of een webapplicatie? Vertel me wat je wilt bereiken, voor wie het bedoeld is en wat er nu lastig gaat.

Je hoeft de technische oplossing nog niet te kennen. Ik denk met je mee over wat nodig is en welke eerste stap past.

Je kunt me mailen op: [info@kevinwebstudio.nl](mailto:info@kevinwebstudio.nl).

Het bestaande kader vraagt aanvullend naar doel, gebruikers, huidige knelpunten en eventuele planning. De drie bestaande stappen onderaan de contactpagina zijn inhoudelijk afgestemd op de werkwijze.

## Gewijzigde bestanden

| Bestand | Wijziging |
| --- | --- |
| `resources/js/pages/Home.vue` | Kop, intro, concrete klantvragen en werkwijze |
| `resources/js/pages/About.vue` | Persoonlijke tekstbasis en contactlink |
| `resources/js/pages/Contact.vue` | Uitnodigende tekst, gespreksvragen en zakelijke mailto-link |
| `resources/js/pages/Services/Index.vue` | Diensten vanuit de vraag van de bezoeker |
| `resources/js/pages/Services/Websites.vue` | Voorbeelden, inhoud en beheer afspreken |
| `resources/js/pages/Services/Dashboards.vue` | Aanvragen en werkzaamheden overzichtelijk maken |
| `resources/js/pages/Services/Webshops.vue` | Catalogus, bestellen en benodigde koppelingen afstemmen |
| `resources/js/app.ts` | Merknaam in de publieke paginatitels |
| `config/projects.php` | Samenvatting en introductie van de bestaande case |
| `docs/brand/ontwerpkeuzes.md` | Bevestigd zakelijk adres en huidige contactopzet |
| `docs/strategy/teksten-stap-4.md` | Dit verslag |
| `docs/strategy/screenshots/stap-4/` | Zes definitieve screenshots van Home, Over mij en Contact |

Authenticatie, database, CI, SSR-instellingen, mailconfiguratie en serverinstellingen zijn ongewijzigd. GeoFortForm4 is niet aangeraakt. Er zijn geen packages toegevoegd.

## Controles

| Controle | Resultaat |
| --- | --- |
| `pnpm format:check` | Geslaagd |
| `pnpm lint:check` | Geslaagd |
| `pnpm types:check` | Geslaagd |
| `pnpm build` | Geslaagd, inclusief Wayfinder met PHP 8.4.15 vooraan in het tijdelijke terminal-PATH |
| PHP 8.4.15 `artisan test` | 103 tests geslaagd, 443 assertions |
| PHP 8.4.15 `vendor/bin/pint --test config/projects.php` | Geslaagd |
| PHP 8.4.15 `vendor/bin/phpstan analyse --memory-limit=512M --no-progress` | Geslaagd, 0 fouten |
| Composer validate | Geslaagd |
| `git diff --check` | Geslaagd |
| Browser op 1440 en 390 px | Tien publieke pagina’s gecontroleerd; geen horizontale overloop of afgekapte teksten |
| Koppen en titels | Eén h1 en één passende, unieke documenttitel per pagina; geen overgeslagen kopniveaus |
| Links en toetsenbord | Bestaande bestemmingen, zichtbare focus op alle links; navigatie naar case en Contact werkt, ook met Tab en Enter |
| E-mailadres | Exacte native mailto-bestemming en zichtbare focus gecontroleerd; aanraakhoogte 44px |
| Browserfouten | Geen JavaScript-, console- of HTTP-fouten |

De tien gecontroleerde routes zijn `/`, `/werk`, `/werk/onderwijsformulier`, `/diensten`, de drie bestaande dienstendetailroutes, `/over-mij`, `/contact` en `/privacy`. De screenshots zijn visueel bekeken. De pagina’s gebruiken de bestaande lokale app op poort 8000 en de productiebuild; er zijn geen runtime-instellingen aangepast. Tijdelijke scripts, browserprofiel, extra screenshots en browsermetingen staan buiten het project onder de tijdelijke map `kevin-webstudio-content-step4`.

De browsercontrole gebruikt Chrome-emulatie, geen fysieke telefoon. De bestaande buildmelding over optionele Fontaine-ondersteuning blokkeert de build niet. Er is geen e-mail verstuurd en de technische bezorging van het zakelijke adres is niet getest.

## Screenshots

| Pagina | Desktop | Mobiel |
| --- | --- | --- |
| Home | [1440 px](screenshots/stap-4/home-1440.png) | [390 px](screenshots/stap-4/home-390.png) |
| Over mij | [1440 px](screenshots/stap-4/over-mij-1440.png) | [390 px](screenshots/stap-4/over-mij-390.png) |
| Contact | [1440 px](screenshots/stap-4/contact-1440.png) | [390 px](screenshots/stap-4/contact-390.png) |

## Voor de eerste release

Het echte contactformulier met zakelijke e-mail en de interactieve onderwijsformulier-demo blijven beide verplicht voor de eerste release. De huidige mailto-link en case vervangen die release-eisen niet. Prijzen, pakketgrenzen, doorlooptijden en onderhoudsafspraken volgen in stap 5. Merge en deployment volgen afzonderlijk.
