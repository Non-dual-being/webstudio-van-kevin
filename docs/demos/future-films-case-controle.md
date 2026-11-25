# Future Films — tweede portfolio-case

Uitgevoerd op 24–25 november 2026 in `C:\wamp64\www\kevin-webstudio`, op `feature/public-visual-refinement`, vanaf `9e6aa6b`. Geen deployment en geen wijzigingen aan Future Films.

## Oplevering

Een tweede kaart op Werk en `/werk/future-films`, met doel, gebruikers, themakeuze, filmreeks, bediening, echte screenshots, een link naar de bestaande website en een contactactie. De Werk-intro past nu bij beide projecten. Home houdt de onderwijscase als uitgelicht project.

Het gedeelde template haalt koppen, demoknopteksten en de melding over een nog niet gepubliceerde demo uit de projectdata. De goedgekeurde onderwijscase behoudt zijn teksten, afbeeldingen en demoroute. Mobiele beeldafmetingen zijn per afbeelding vastgelegd. Future Films krijgt geen onderwijsdemo-onderdelen.

Vier WebP-beelden rechtstreeks vastgelegd op de live site: Mobiliteit en Watermanagement geselecteerd, en de introductiefilm gepauzeerd in Future Cinema; elk op desktop en mobiel. De mobiele screenshots tonen de volledige pagina. Geen gegenereerde beelden of nagebouwde interface.

## Bron en versie

Onderzocht: [de live Future Films-site](https://futurefilms.planetaryhealth.xyz/) en de bijbehorende GitHub-broncode.

De actuele toepassing hoort bij **`Non-dual-being/FutureFilmsindeKlas`**, niet bij de oudere openbare repository `Non-dual-being/FutureFilms`. Die oudere repository bevat statische HTML in `0. All_levels` en `1. Level_1`. De live toepassing heeft de PHP-pagina's, jukebox en cinema van `FutureFilmsindeKlas`.

Bronpunt: [commit `14723198f4b6eb94a9d3ffc2bf5e9f33a45aedc4`](https://github.com/Non-dual-being/FutureFilmsindeKlas/commit/14723198f4b6eb94a9d3ffc2bf5e9f33a45aedc4), `main` op het controlemoment, 4 november 2026, `fix: keep selector header visible in short landscape and restore scrollbar`.

**46 van 46 publieke JavaScript- en CSS-bestanden kwamen byte voor byte overeen** met de Git-blobhashes van die commit. Gecontroleerd: `public/scripts/public`, `public/scripts/shared`, `public/styles/index`, `public/styles/video` en `public/styles/shared`. Ook de laatste wijziging aan `short-landscape.css` komt overeen. De live HTML bevat dezelfde zichtbare structuur en assetverwijzingen als `public/index.php` en `public/videoplayer.php`.

Dit verifieert de publieke frontend tegen de genoemde bronversie. Een volledige backenddeployment is daarmee niet bewezen: er is geen publiek deploymentmanifest of server-Git-SHA vastgesteld. De bronrepository is privé; er zijn geen bronbestanden, inloggegevens of beheerinformatie overgenomen in dit portfolio.

| Case-informatie | Bron |
| --- | --- |
| Acht thema's, minimaal twee keuzes, teller en startactie | Live selector; `selectionscript.js`, `selector-experience.js`, `videolist.js` |
| Filmreeks uit geselecteerde thema's | `selectionscript.js`, `videoscript.js` |
| Speel/pauze, vorige/volgende, filmstrook, shuffle, bioscoopmodus en volume | Live Future Cinema; `videoplayer.php`, `videoscript.js` |
| Gebruik in de klas; handleiding en spelbord | Toepassings-/repositorynaam, onderwijsniveau in de selectorbron en live downloadmenu |
| JavaScript, PHP, CSS en HTML-video | Gelezen bronbestanden en live HTML |

Geen verzonnen resultaten, quotes, opdrachtgeverrelatie, lesresultaten of persoonlijke bijdragen. De uitdaging beschrijft de gebruikershandelingen die deze toepassing ondersteunt, geen onbevestigd historisch klantprobleem.

## Ontbrekende informatie voor beoordeling

Kevins precieze bijdrage en de verdeling van ontwerp, ontwikkeling en film-/illustratiemateriaal zijn nog niet vastgesteld. Ook toestemming voor publicatie van het gebruikte projectbeeld is niet afzonderlijk gedocumenteerd. Daarom zijn geen auteurschapsclaims toegevoegd. De screenshots zijn opgenomen voor de gevraagde lokale beoordeling; er is niets gedeployd.

## Controles

- Volledige ESLint, Prettier, TypeScript, productiebuild en Pint voor de gewijzigde PHP-bestanden geslaagd.
- PublicPagesTest: **16 tests / 165 assertions geslaagd**. Inclusief tweede projectkaart, centrale projectdata en afwezigheid van een onderwijsdemolink bij Future Films, ook als de onderwijsdemo aanstaat.
- **54 browsercontroles geslaagd** op 390 en 1440 pixels: Werk en beide cases, demo aan/uit, geladen beelden, eigen koppen, projectlink, contactroute en zichtbare toetsenbordfocus. Geen horizontale overloop, JavaScript-excepties of externe browserrequests vanuit de lokale pagina's.
- Werk, beide cases en de focusweergave daadwerkelijk als screenshots geopend en visueel beoordeeld op beide breedtes. Ook alle vier oorspronkelijke projectbeelden bekeken.
- De bestaande volledige onderwijsdemoroute opnieuw geslaagd: **24 controles per breedte**, inclusief fictieve aanvraag, bijwerken van aantallen en prijs, definitief maken, reset en terugkeer naar de case. Geen demobroncode of bedrijfslogica gewijzigd.

De build heeft alleen de bestaande niet-blokkerende meldingen over optionele fontaine en pluginlooptijd. De eerste nieuwe browserhelper liep tegen een Node/Undici-netwerkfout aan; de HTTP-controle gebruikt daarna de browserrespons. De volledige controle is opnieuw uitgevoerd en geslaagd. Geen fysieke telefoon of volledige schermlezeraudit uitgevoerd.

## Lokale preview

- [Werk](http://127.0.0.1:8136/werk)
- [Future Films-case](http://127.0.0.1:8136/werk/future-films)
- [Onderwijscase en demoroute](http://127.0.0.1:8136/werk/onderwijsformulier)

De bestaande geïsoleerde preview gebruikt PHP 8.4.15, gebouwde assets, een eigen SQLite-database en eigen sessies/caches. Mail is arraytransport, zonder worker of scheduler. De normale `.env` is niet veranderd. Onderwijsdemo: poort 8137; aparte websitepreview met demo uit: poort 8138.

Herstart zolang de tijdelijke runtime/helpers aanwezig zijn:

```powershell
Set-Location -LiteralPath 'C:\wamp64\www\kevin-webstudio'
node "$env:TEMP\kevin-onderwijscase-20261113\start.mjs"
```

Bewijs en helpers staan buiten Git in `%TEMP%\kevin-future-films-20261124`: `asset-matches.json`, screenshots, controlelogs en `browser-results.json`. De bestaande demoroutecontrole staat onder `%TEMP%\kevin-onderwijscase-20261113`.

## Git-afbakening

Alleen projectdata, gedeelde casepresentatie, Werk-intro, bijbehorende types/tests, vier nieuwe afbeeldingen en dit verslag horen bij deze taak. Bestaande wijzigingen in README en het lokale ontwikkelrunbook en de ongetrackte AGENTS-/RUN-bestanden blijven buiten de commit. Geen dependencies, deploymentbestanden, onderwijsafbeeldingen of demobroncode gewijzigd.

Na oplevering wachten op Kevins feedback; geen volgende case beginnen.
