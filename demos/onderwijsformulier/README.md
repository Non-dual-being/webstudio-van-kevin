# Onderwijsdemo: formulier en aanvragenbeheer

Publicatievoorbereiding: zie [eerste publicatie](../../docs/runbooks/eerste-publicatie.md). Nog niet live. De hoofdsiteknop blijft uit via `EDUCATION_DEMO_ENABLED=false`; de demo-teruglink verschijnt alleen in een build met `VITE_WEBSTUDIO_LINK_ENABLED=true`. Deze twee gewone links openen in hetzelfde tabblad. De lokale PHP 8.4.15-versiecontrole blijft behouden; op deze machine is die versie voor run 13A afzonderlijk in de tijdelijke testmap beschikbaar gemaakt.

Zelfstandige Vue/Vite-demo met een kleine PHP-laag. Laravel en GeoFortForm4 hoeven niet te draaien of aanwezig te zijn. Alleen fictieve gegevens; aanvragen en wijzigingen staan tijdelijk in het geheugen van het open tabblad. Geen database, serversessies, mailtransport of queue.

## Starten op Windows

```powershell
Set-Location -LiteralPath 'C:\wamp64\www\kevin-webstudio\demos\onderwijsformulier'
npm.cmd ci
npm.cmd run build
npm.cmd start
```

Open het **formulier op http://127.0.0.1:8120/#/formulier** of het **dashboard op http://127.0.0.1:8120/#/aanvragen**. Gebruik binnen de demo de navigatielinks om dezelfde omgeving te behouden. Een ander tabblad begint zelfstandig met 100 voorbeelden. Stop de server met Ctrl+C. Hij luistert uitsluitend op localhost en serveert de voltooide build plus negen expliciete `/demo-api/`-routes. Na frontendwijzigingen opnieuw bouwen en vernieuwen. Stop geen bestaand proces als 8120 bezet is: stel bijvoorbeeld `$env:DEMO_PORT = '8122'` in en gebruik die poort in beide URL’s.

Getest met Node 22.23.1 en WAMP **PHP 8.4.15**. Het startcommando controleert die PHP-versie en gebruikt standaard `C:/wamp64/bin/php/php8.4.15/php.exe`. Met `DEMO_PHP` kan hetzelfde versienummer elders worden aangewezen; `DEMO_PORT` verandert de poort. Geen `.env` vereist of ingelezen. De bestaande SMTP-configuratie wordt niet gebruikt.

## Uitproberen

- School en contactpersoon zijn al fictief ingevuld. Gebruik ook bij wijzigingen alleen verzonnen gegevens.
- Kies een datum en onderwijssector. Woensdag biedt voor primair onderwijs ook het ochtendprogramma. Niveaus, groepen en modules bepalen welke vervolgvelden verschijnen.
- Verander leerlingen, begeleiders en catering. De server berekent de voorbeeldprijs met de oorspronkelijke prijsregels en de fictieve [catalogus](server/catalog.json).
- De kalender toont naast vrije dagen ook beperkte capaciteit, volle dagen, een sluitingsdag en een fictieve vakantie. Datums schuiven met de huidige dag in `Europe/Amsterdam` mee. Er wordt geen echte beschikbaarheid geraadpleegd.
- Het rooster toont de bestaande unavailable-toestand met modules en groepsaantal. Deze eerste demo bevat geen roosterafbeeldingen, downloads of planner.
- Rond af met de demo-afspraak. De aanvraag wordt één keer toegevoegd met status **In optie**. **Bekijk in het demodashboard** opent precies die aanvraag.
- Zoek een school, filter op status, programma of bezoekdatum en sorteer op bezoekdatum. De lijst toont 20 aanvragen per pagina. Aantallen en de som van voorbeeldprijzen volgen de huidige selectie; dit is geen gerealiseerde omzet.
- Open een aanvraag en wijzig school/contactgegevens, leerlingen, begeleiders, catering of opmerkingen. PHP valideert en herberekent de voorbeeldprijs. Programma, onderwijsselectie en bezoekdatum worden in deze versie alleen getoond.
- Gebruik **Definitief maken**, **Afwijzen** of **Terug naar In optie**. Alleen definitieve aanvragen tellen mee voor de kalendercapaciteit. De beperkte demonstratierol mag geen regels overschrijven en verstuurt nooit mail.
- **Demo opnieuw starten** en browserrefresh herstellen de beginset van 100 en wissen eigen aanvragen. Reset wist ook formulierinvoer, filters, fouten, berekeningen, kalenderinstanties en caches. Requests worden afgebroken; een generatiecontrole weigert late antwoorden. Een oude detail-URL naar een verdwenen eigen aanvraag toont een uitleg.

## Dataset en opslag

`server/Seed.php` gebruikt vaste seed **120100** en `DemoClock` in **Europe/Amsterdam**. `DEMO_TODAY` injecteert een vaste datum; standaard geldt vandaag. De set bevat exact 100 verschillende Demoscholen met één aanvraag per school, synthetische contactpersonen en `example.invalid`-adressen. Mail- en telefoonlinks zijn niet actief. Er zijn recente historische bezoeken, toekomstige bezoeken, drie sectoren, twee programma’s, verschillende groepen/cateringkeuzes en alle drie bronstatussen. De eerste vier records maken herkenbare dagen met beperkte of volle capaciteit; sluiting en vakantie zijn fictieve blokkades.

Eén gedeelde Vue-state bevat de actuele records. PHP ontvangt per aanvraag uitsluitend de benodigde invoer en een compacte capaciteitssamenvatting; het bewaart niets tussen requests. Prijzen komen uit de bestaande fictieve catalogus. Geen cookies, local/session storage, IndexedDB of gedeelde veranderbare serverstate. Maximaal 20 eigen aanvragen extra (120 totaal), 32 KiB per request en 1000 leerlingen als technische demogrens. Beheer kan vanaf 1 leerling; de bron stelt aanvullende eisen aan een nieuwe publieke aanvraag en aan definitief maken. In optie/afgewezen mogen bepaalde beleidsafwijkingen als aandachtspunt blijven staan. Definitieve wijzigingen worden zonder overridebevoegdheid geweigerd bij programma-, begeleider- of capaciteitsproblemen.

## Controles

```powershell
npm.cmd run check
npm.cmd run build
npm.cmd run test:browser
```

Build en browsertest lopen na elkaar. `check` voert typecontrole, ESLint, PHP-syntaxcontrole, domeintests en de isolatiecontrole uit. De browsertests starten zelf PHP op **8121**, met vaste datum **2031-10-06**, en eigen headless Chrome-profielen. Het dashboard wordt in twee afzonderlijke browsercontexten gecontroleerd. Alleen eigen processen worden gesloten. Geen bestaande server, database of contactqueue wordt gebruikt. Chrome staat standaard in `C:/Program Files/Google/Chrome/Application/chrome.exe`; eventueel aan te passen via `DEMO_CHROME`.

JSON-controleresultaten komen in de genegeerde map `artifacts/`. Zes representatieve dashboardbeelden staan in `../../docs/demos/screenshots/onderwijsdashboard-demo/`. De tests bevatten alleen fictieve data en rapporteren geen formulierinhoud. De lokale server bewaart geen requestlogs. Deze eigenschap moet bij hosting ook voor reverse-proxy-, toegangs- en foutlogs worden ingericht.

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

Het aansluitende [dashboardbronmanifest](dashboard-source-manifest.json) legt de extra selectie vast: statusbeleid, beheervalidatie, capaciteit, school/contactvalidatie, types en beheerknoppen. Alleen-lezen interfaces vervangen SQL-afhankelijkheden; transacties, productieauthenticatie, prijsopslag en mail zijn niet geïmporteerd. `server/Dashboard.php` coördineert de geselecteerde regels binnen één tijdelijke request. De productiebeveiliging is nergens gewijzigd.

Het actuele [dashboardcontrolerapport](../../docs/demos/onderwijsdashboard-demo-controle.md) beschrijft bewezen gedrag en publicatievoorwaarden. Het [eerdere formulierrapport](../../docs/demos/onderwijsformulier-demo-controle.md) blijft als historie behouden. De volledige roosterplanner, personeelsplanning, gebruikersbeheer, exports, facturen en echte verzending zijn vervolgwerk. Publicatierechten, hostingisolatie en de bestaande privacy/mailcontroles blijven open. Geen publieke URL, portfolioknop of deployment; de ingebouwde PHP-server is alleen een lokale previewserver.
