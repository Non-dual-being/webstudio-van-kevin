# Onderwijscase en lokale demoroute

Afgerond op 19 november 2026. Uitgevoerd in `C:\wamp64\www\kevin-webstudio`, op `feature/public-visual-refinement`, vanaf `85d3dc9468f7ec6eba3bc8d55622f1cb467ea431`. Onderbrekingen tijdens de run zijn hervat na controle van de bestanden en processen. Geen volgende case of publicatiestap begonnen.

## Inhoud en beelden

- `config/projects.php` beschrijft nu **Onderwijsaanvragen: van formulier tot dashboard**: het probleem, samenhangende formulierkeuzes, binnenkomst als In optie en opvolging. Home en Werk gebruiken dezelfde projectgegevens. Andere Home-inhoud en diensten zijn behouden.
- Twee toegelichte beelden en een korte probeerroute toegevoegd aan `Work/Show`, met de bijbehorende presentatietypes. De probeerroute verschijnt uitsluitend wanneer de bestaande `demoUrl` beschikbaar is. Zonder vrijgave blijven tekst, beelden en contactactie bruikbaar, met de melding dat de interactieve demo nog niet publiek beschikbaar is.
- De case beperkt zich tot bewezen demogedrag. Geen claims over echte e-mail, opslag in een database of een uitgewerkt conceptrooster. Programma, datum en onderwijskeuze zijn in beheer alleen leesbaar; de afwezige planner, personeelsplanning, exports, facturen en e-mail zijn benoemd. PHPMailer is uit de demotechnieken verwijderd.
- Echte viewportopnamen uit de werkende demo, uitsluitend fictieve gegevens, zonder browserchrome of gegenereerde interface: `public/images/projects/onderwijsformulier/`. Formulier en dashboard zijn elk 1200 × 800; aparte mobiele uitsneden zijn 390 × 800. WebP, respectievelijk 48.988 / 49.822 / 32.766 / 22.410 bytes (circa 150 KiB totaal). Budget: maximaal 100 KiB per beeld. Expliciete afmetingen, alt-teksten, onderschriften en lazy loading; mobiele bron via `picture`.

## Lintcorrectie

Afzonderlijke commit **`382524c` — `style: resolve release script lint errors`**. De 65 fouten in `deploy/prepare.mjs` en `deploy/render.mjs` zijn gereproduceerd en uitsluitend daar opgelost met ESLint en gerichte formattering. Diff handmatig bekeken; een genormaliseerde AST-vergelijking bevestigde gelijke programmastructuur buiten de toegevoegde accolades rond losse statements. Geen andere releasevoorwaarden, controles, paden, kopieerlijsten, dependencies of runtimeconfiguratie gewijzigd.

Volledige `pnpm lint:check`, beide `node --check`-controles en `node deploy/check.mjs` geslaagd. Geen releasepakket gemaakt en geen serveractie uitgevoerd voor deze correctie.

## Lokale koppeling en isolatie

De centrale publieke URL in `config/demos.php`, `EDUCATION_DEMO_ENABLED=false` als standaard en de bestaande controllercontrole zijn behouden. De tijdelijke previewrouter overschrijft uitsluitend zijn eigen configuratie naar de werkelijk gestarte lokale demo. Er is geen URL uit bezoekersinvoer.

De enige uitbreiding in democode is `resources/js/websiteLink.ts`: buildmodus `local-preview` kiest exact `http://127.0.0.1:8136/werk/onderwijsformulier`, alle andere modi exact de bestaande publieke case-URL. De bestaande buildflag blijft vereist. De isolatiecheck staat alleen die twee vaste navigatiebestemmingen toe en controleert de modusvoorwaarde; overige externe koppelingen, opslag- en mailconnectors blijven verboden. CSP en API zijn ongewijzigd.

De demobuild en een kopie van alleen `server`/`src` staan in een tijdelijke previewmap. De gewone demo-installatie en `dist` zijn niet voor de kijkversie gewijzigd. Een aparte standaardbuild is gecontroleerd op de publieke teruglink en afwezigheid van de lokale bestemming. Links tussen website en demo zijn gewone anchors in hetzelfde tabblad. Formulier en dashboard gebruiken dezelfde hashrouter en behouden daarmee de eigen aanvraag binnen dat document. Refresh/reset/opnieuw openen herstellen de voorbeelden.

De verdwenen runtime van run 13A is opnieuw uit het officiële PHP Windows-archief gehaald naar een aparte tijdelijke map: **PHP 8.4.15**, gecontroleerd met de executable. Node **22.23.1**, pnpm **11.5.2**. Geen globale runtime of versiegrens gewijzigd. Hoofdsitepreview gebruikt eigen SQLite, sessies, views en caches; de previewrouter gebruikt een eigen environmentpad. Mail staat op arraytransport, zonder worker, scheduler of verwerking van de bestaande contactqueue. Geen echte `.env` gewijzigd, echte aanvraag gelezen of mail verzonden; GeoFortForm4 is niet benaderd.

## Controles

| Controle | Resultaat |
| --- | --- |
| Hoofdsite lint, formattering, types, productiebuild | Geslaagd |
| Pint voor gewijzigde PHP-bestanden; PHPStan | Geslaagd, geen PHPStan-fouten |
| PublicPages-tests na build | 14 tests, 139 assertions; centrale data, demo uit/aan, bestaande routes en onbekende slug |
| Demo types, ESLint en isolatie | Geslaagd; 65 PHP- en 74 frontendbestanden in isolatiecheck |
| Afzonderlijke preview- en standaarddemobuild | Geslaagd; standaardbuild bevat geen lokale teruglink |
| Releasecontractcheck na linkwijziging | Geslaagd; geen Nginx/FPM-uitvoering |
| Home, Werk, case op 1440 en 390 px | Gedeelde scope, werkende kaartlinks, geladen beelden, geen horizontale overloop |
| Hele bezoekersroute op beide breedtes | 24 controles per breedte; fictieve aanvraag 101 in optie, exact detail, 40→48 leerlingen en 5→6 begeleiders, prijs €290→€348, definitief maken, reset naar 100, terug naar case |
| Toetsenbord en netwerk | Probeeractie en teruglink bereikbaar met zichtbare focus; geen externe browserrequests of JavaScript-excepties |
| Demo uit op aparte preview | Beide beelden laden bij scrollen op 1440 en 390 px; inhoud werkt, geen dode probeeractie |
| Laatste controle op 19 november | Publieke routes HTTP200, onbekende slug 404; seed100, CSP same-origin, private demoroutes 404; Pint en demo-ESLint geslaagd |

De vier publicatiebeelden, Home/Werk/case op beide breedtes, het eigen aanvraagdetail en de mobiele focusweergave zijn daadwerkelijk geopend en bekeken. Geen fysieke telefoon of volledige schermlezeraudit uitgevoerd. De volledige domeinregressie is niet herhaald: bedrijfsregels en gegevensgedrag zijn niet veranderd. Buildwaarschuwingen over optionele fontaine en pluginlooptijd blokkeren de build niet.

De eerste browserpoging vond de probeeractie niet doordat de controlehelper de heading als bovenliggend element gebruikte. Alleen de helperselector is gecorrigeerd; de volledige gekoppelde route slaagde daarna. Testlogs, screenshots en helpers staan buiten Git in onderstaande tijdelijke map.

## Bekijken, starten en stoppen

- [Werk](http://127.0.0.1:8136/werk)
- [Onderwijscase](http://127.0.0.1:8136/werk/onderwijsformulier)
- [Formulierdemo](http://127.0.0.1:8137/#/formulier)
- [Demodashboard](http://127.0.0.1:8137/#/aanvragen)
- [Case met demo uit](http://127.0.0.1:8138/werk/onderwijsformulier)

Alleen localhost. Gebruik vanuit de case de probeerknop en vervolgens de navigatie binnen de demo om dezelfde aanvraag te behouden.

Zolang de tijdelijke runtime en helpers aanwezig zijn:

```powershell
Set-Location -LiteralPath 'C:\wamp64\www\kevin-webstudio'
node "$env:TEMP\kevin-onderwijscase-20261113\start.mjs"
# Alleen de drie eigen previews stoppen:
node "$env:TEMP\kevin-onderwijscase-20261113\start.mjs" --stop
```

De starter controleert PHP 8.4.15 en vrije poorten, herkent uitsluitend deze preview en registreert zijn eigen PIDs. Stoppen controleert de previewmarker vóór het stoppen van die processen. Andere servers blijven ongemoeid. Bij ontbrekende tijdelijke bestanden eerst opnieuw een geïsoleerde omgeving voorbereiden; gebruik niet stilzwijgend de echte ontwikkelconfiguratie.

Een demowijziging opnieuw bouwen: stop eerst deze eigen preview, voer `node "$env:TEMP\kevin-onderwijscase-20261113\checks.mjs" --build` uit en start opnieuw. De helper bouwt de hoofdsite en twee afzonderlijke demobuilds; geen releaseverpakking of deployment. `checks.mjs` zonder argument draait de beschreven codecontroles; `browser.mjs` controleert de gekoppelde route en `browser.mjs --capture` maakt de fictieve publicatiebeelden opnieuw.

## Git en publicatiestatus

Lintcorrectie en casewijzigingen zijn afzonderlijke commits; de casecommit is herkenbaar aan `feat: explain education workflow and connect local demo preview`. Alleen eigen beoordeelde bestanden worden opgenomen en beide commits gaan naar `feature/public-visual-refinement`. De bestaande wijzigingen in README en `docs/runbooks/lokaal-ontwikkelen-en-git.md` en de ongetrackte AGENTS-/RUN-bestanden blijven buiten de commits.

Niets gepubliceerd, gemerged of aan DNS gewijzigd. Hosting/HTTPS/isolatie, publicatierechten en de overige voorwaarden uit [eerste publicatie](../runbooks/eerste-publicatie.md) blijven gelden. De publieke demoknop blijft standaard uit. Deze kijkversie wacht op Kevins beoordeling; geen volgende case of publicatiestap zonder feedback.
