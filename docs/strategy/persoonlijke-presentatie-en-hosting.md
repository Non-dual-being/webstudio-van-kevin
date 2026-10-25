# Persoonlijke presentatie en hosting

25 oktober 2026. Lokale inhoudelijke stap vanaf `fdcf6c3280b8eee8db9e1ffb86545ca99fe75b77` op `feature/public-visual-refinement`. Eén samenhangende versie voor Kevins beoordeling; geen volgende portfolio- of publicatierun begonnen.

## Bevestigde uitgangspunten

Kevin bouwt websites en webapplicaties. Zijn kracht is uitgebreide functionaliteit begrijpelijk maken met een duidelijke opbouw, intuïtieve navigatie en logische vervolgstappen. Zorgvuldig doorwerken aan de afgesproken oplossing vertaalt zich naar begrijpelijke keuzes, prettig gebruik en aandacht voor afwerking. Het is geen toezegging van onbeperkte revisies, foutloosheid, permanente beschikbaarheid of onbeperkte nazorg.

Hij kan daarnaast VPS'en en dedicated servers inrichten, hosting verzorgen en een website online zetten. Hosting, updates, back-ups, herstel en ondersteuning worden vooraf afgesproken. Er is geen automatisch inbegrepen abonnement of vastgelegde SLA. Nieuwe functies en doorontwikkeling worden apart besproken en begroot.

Contact verloopt via het bestaande contactformulier en **info@kevinwebstudio.nl**. Verdere zakelijke afstemming gaat voorlopig per e-mail. Omvang, planning en geldafspraken worden tijdens de projectbespreking bepaald. De publieke prijsvermelding is consequent **“Prijs na projectbespreking”**; geen bedragen, vaste pakketgrenzen of standaarddoorlooptijden.

Deze keuzes actualiseren de tekstbasis uit [positionering](positionering.md) en het [dienstenvoorstel](diensten-en-pakketten.md). De oudere interne pakketvergelijking wordt hiermee niet vastgesteld als aanbod of voorwaarden. Historische bewijsstukken blijven behouden.

## Toepassing in de website

- **Home:** persoonlijke intro met de onderscheidende kracht vroeg in beeld; aanbod en werkwijze leggen nadruk op gebruik, gerichte feedback, afwerking en vooraf gemaakte afspraken. De bestaande werk-/dienstenknoppen en onderwijs-case blijven behouden.
- **Over mij:** eerst begrijpen wat gebruikers moeten kunnen doen, informatie en regels tot een logische route terugbrengen, tussentijds een werkende versie tonen, details controleren en feedback verwerken. Oplevering, hosting en verder beheer worden vooraf besproken. Geen biografie, ervaringstermijn, certificeringen of portret toegevoegd.
- **Diensten:** dezelfde vier aanbodgroepen. De vierde heet op Home én Diensten **“Hosting, onderhoud en doorontwikkeling”** en heeft één rustige actie **“Bespreek hosting en beheer”** naar `/contact`. De toelichting noemt VPS/dedicated server als mogelijke technische omgeving. Een kort slotblok verduidelijkt de route van werk bekijken via contact en projectbespreking naar een persoonlijk voorstel en prijs.
- **Drie dienstendetailpagina's:** uitsluitend de prijsvermelding afgestemd; geen onnodige herschrijving.
- **Contact:** de bestaande intro, het formulier en de mailto-link sluiten aan en zijn behouden. Geen velden, validatie, opslag, queue, mailtemplates of SMTP-instellingen gewijzigd. De offertefunctie is nog geen beschikbare websitefunctie.

Logo, kleuren, lettertypen, navigatie, componenten en focusstijlen zijn behouden. De onderwijs-case krijgt hier geen inhoudelijke uitbreiding; de nog niet gepubliceerde demo wordt niet als live gepresenteerd.

## Status en vervolg

| Onderdeel | Actuele status |
| --- | --- |
| Technische publicatievoorbereiding | Afgerond in run 13A; geen deployment. Zie [eerste publicatie](../runbooks/eerste-publicatie.md) |
| Persoonlijke presentatie en hostingaanbod | Lokaal bijgewerkt, wacht op Kevins inhoudelijke beoordeling |
| Portfolio en onderwijs-case uitbreiden naar formulier én dashboard | Afzonderlijk volgend kijkmoment; niet uitgevoerd |
| Future Films en Scriptorium | Afzonderlijke portfolio-opdrachten; niet uitgevoerd |
| Simon-siteontwikkeling | Ander project/gesprek; niet uitgevoerd |
| Offertevoorziening | Vervolgwerk; geen automatische offerte of online prijsberekening toegevoegd |
| Publicatievoorwaarden | Linux/FPM-isolatie, echte ontvangst van beide formuliermails en overige productiechecks blijven open |
| Juridische en organisatorische besluiten | Identiteit, leveranciers-/verwerkersafspraken, bewaarbeleid en publicatierechten blijven open; geen onbekende gegevens op publieke pagina's ingevuld |

## Lokaal bekijken en herstarten

- Home: **http://127.0.0.1:8135/**
- Over mij: **http://127.0.0.1:8135/over-mij**
- Diensten: **http://127.0.0.1:8135/diensten**

De preview gebruikt de werkelijk gecontroleerde PHP **8.4.15** onder `%TEMP%/kevin-release-13a-20261024/php84/php.exe`, Node **22.23.1** en pnpm **11.5.2**. Er zijn geen globale runtime-instellingen gewijzigd. De bestaande PHP-server op 8000 en Vite-server blijven ongemoeid. De eigen preview gebruikt de gebouwde assets en negeert de bestaande Vite-hotfile uitsluitend in zijn tijdelijke router.

Herstarten zolang de tijdelijke helpers/runtime aanwezig zijn:

```powershell
Set-Location -LiteralPath 'C:\wamp64\www\kevin-webstudio'
node "$env:TEMP\kevin-persoonlijk-hosting-20261025\start.mjs"
if ($LASTEXITCODE -ne 0) { throw 'Preview kon niet starten' }
```

De helper controleert de PHP-versie, hergebruikt alleen deze herkenbare preview en stopt als de poort anders bezet is. Hij gebruikt een eigen SQLite-database, cache, sessiecookie, views en configuratiecaches in de tijdelijke map. Mail is arraytransport met expliciete testinstelling; er draait geen worker of scheduler. De bestaande ontwikkel-.env en contactqueue worden niet gebruikt voor verzending. Previewdata blijft buiten Git. Als de tijdelijke runtime/helpers ontbreken, eerst opnieuw een geïsoleerde preview voorbereiden.

## Gerichte controles

- `pnpm format:check`, typecontrole, productiebuild en ESLint voor alle zes gewijzigde Vue-pagina's geslaagd. Alleen die zes bestanden zijn met de bestaande formatter verwerkt.
- Na de build: bestaande **PublicPagesTest, 14 tests / 141 assertions geslaagd** op PHP 8.4.15. SQLite in-memory, aparte caches/views, arraymail; geen echte verzending of bestaande queueverwerking. Geen tests toegevoegd die marketingzinnen vastzetten.
- De brede `pnpm lint:check` meldt **65 bestaande stijlfouten** in de ongewijzigde `deploy/prepare.mjs` en `deploy/render.mjs` uit run 13A. De tekstwijzigingen veroorzaken deze niet; geen deploymentcode aangepast voor deze inhoudelijke stap.
- Build heeft alleen de bestaande niet-blokkerende meldingen over optionele fontaine en pluginlooptijd. Geen dependencywijziging.
- Home, Over mij en Diensten gecontroleerd op **390 × 844 en 1440 × 1000**: zes pagina/breedtecombinaties met correcte bestemmingen, zichtbare toetsenbordfocus, geen horizontale overloop, JavaScript-excepties of externe HTTP-verzoeken. De drie dienstendetails en Contact geven HTTP200. De zes volledige paginascreenshots en twee mobiele focusdetails zijn daadwerkelijk geopend en bekeken; tekstafbreking, kaarten en afwerking passen binnen de bestaande stijl.
- Automatisch openen in de ingebouwde appbrowser mislukte door een browserkernfout. De lokale preview en de afzonderlijke Chrome-controle werken; gebruik de directe links hierboven. Geen onopgeloste beperking bij het bekijken van de screenshots.
- De gerichte diffcontrole is schoon. De volledige diffcontrole signaleert daarnaast de al bestaande extra lege eindregel in README.md; die lokale wijziging is behouden. Helpers, controlelogs en screenshots staan onder `%TEMP%/kevin-persoonlijk-hosting-20261025`, buiten Git.

Na oplevering beoordeelt Kevin uitsluitend: herkenbaarheid van intro/Over mij, duidelijkheid van bouwen en hosting/beheer, en de route naar contact en prijsafspraken. Daarna wachten op feedback; geen automatische volgende inhoudelijke stap.
