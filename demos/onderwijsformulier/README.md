# Onderwijsformulier-demo

Zelfstandige Vue/Vite-demo met een kleine PHP-laag. Laravel en GeoFortForm4 hoeven niet te draaien of aanwezig te zijn. Alleen fictieve gegevens; geen database, sessies, aanvraagopslag, mailtransport of queue.

## Starten op Windows

```powershell
Set-Location -LiteralPath 'C:\wamp64\www\kevin-webstudio\demos\onderwijsformulier'
npm.cmd ci
npm.cmd run build
npm.cmd start
```

Open **http://127.0.0.1:8120**. Stop met Ctrl+C. De server luistert uitsluitend op localhost en serveert de voltooide build plus zeven expliciete `/demo-api/`-routes. Er is geen Vite-devserver nodig. Na frontendwijzigingen opnieuw bouwen; daarna de pagina vernieuwen.

Getest met Node 22.23.1 en WAMP **PHP 8.4.15**. Het startcommando controleert die PHP-versie en gebruikt standaard `C:/wamp64/bin/php/php8.4.15/php.exe`. Met `DEMO_PHP` kan hetzelfde versienummer elders worden aangewezen; `DEMO_PORT` verandert de poort. Geen `.env` vereist of ingelezen. De bestaande SMTP-configuratie wordt niet gebruikt.

## Uitproberen

- School en contactpersoon zijn al fictief ingevuld. Gebruik ook bij wijzigingen alleen verzonnen gegevens.
- Kies een datum en onderwijssector. Woensdag biedt voor primair onderwijs ook het ochtendprogramma. Niveaus, groepen en modules bepalen welke vervolgvelden verschijnen.
- Verander leerlingen, begeleiders en catering. De server berekent de voorbeeldprijs met de oorspronkelijke prijsregels en de fictieve [catalogus](server/catalog.json).
- De kalender toont naast vrije dagen ook beperkte capaciteit, volle dagen, een sluitingsdag en een fictieve vakantie. Datums schuiven met de huidige dag in `Europe/Amsterdam` mee. Er wordt geen echte beschikbaarheid geraadpleegd.
- Het rooster toont de bestaande unavailable-toestand met modules en groepsaantal. Deze eerste demo bevat geen roosterafbeeldingen, downloads of planner.
- Rond af met de demo-afspraak. De bevestiging luidt: “Demo afgerond. Er is geen aanvraag opgeslagen of e-mail verstuurd.”
- **Demo opnieuw starten** wist invoer, fouten, berekeningen, kalenderinstanties en caches. Requests worden afgebroken; een generatiecontrole weigert ook late antwoorden die annuleren negeren.

## Controles

```powershell
npm.cmd run check
npm.cmd run build
npm.cmd run test:browser
```

Build en browsertest lopen na elkaar. `check` voert typecontrole, ESLint, PHP-syntaxcontrole, domeintests en de isolatiecontrole uit. De browsertest start zelf PHP op **8121**, met een vaste fictieve datumbron, en een eigen headless Chrome-profiel. Hij sluit uitsluitend die eigen processen. Geen bestaande server, database of contactqueue wordt gebruikt. Chrome staat standaard in `C:/Program Files/Google/Chrome/Application/chrome.exe`; eventueel aan te passen via `DEMO_CHROME`.

Screenshots en het JSON-controleresultaat komen in de genegeerde map `artifacts/`. De tests bevatten alleen fictieve data en rapporteren geen formulierinhoud. De lokale server bewaart geen requestlogs. Deze eigenschap moet bij eventuele hosting ook voor reverse-proxy-, toegangs- en foutlogs worden ingericht.

Voor een vaste kalender tijdens handmatig testen:

```powershell
$env:DEMO_TODAY = '2031-10-06'
npm.cmd start
# Na stoppen terug naar de werkelijke dag:
Remove-Item Env:DEMO_TODAY
```

`DEMO_TODAY` is alleen een serverinstelling; bezoekers kunnen deze niet via een request instellen. Kalender, frontenddatumvalidatie en serverdatumvalidatie gebruiken dezelfde datumbron.

## Herkomst en grenzen

Het [bronmanifest](source-manifest.json) legt de geselecteerde bestanden, revisie en oorspronkelijke SHA-256-hashes vast. De `GeoFort`-namespace en technische formulierkeys blijven intern behouden voor herkenbaar hergebruik. Publieke teksten, assets, tarieven en API-koppelingen zijn voor de demo vervangen. De drie alleen-lezen interfaces staan in `server/Contracts`; de fictieve repositories bevatten geen SQL. `ValidateSubmission` is de oorspronkelijke validatiesequentie tot en met capaciteit, zonder de daaropvolgende opslag, cooldownregistratie en verzending.

Het actuele [controlerapport](../../docs/demos/onderwijsformulier-demo-controle.md) beschrijft bewezen gedrag en publicatievoorwaarden. Er is geen publieke URL, portfolioknop of deployment toegevoegd. De ingebouwde PHP-server is alleen een lokale previewserver.
