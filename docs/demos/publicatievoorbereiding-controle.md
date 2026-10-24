# Publicatievoorbereiding — controle run 13A

24 oktober 2026. Basis `642cae3`, branch `feature/public-visual-refinement`. Uitvoerplan: [eerste-publicatie](../runbooks/eerste-publicatie.md). Geen deployment, merge, DNS/certificaat-/servicewijziging of echte mail. GeoFortForm4 en de bestaande ontwikkelomgeving zijn behouden.

## Uitgevoerd

| Controle | Resultaat en reikwijdte |
| --- | --- |
| PHP 8.4.15 + PHP 8.5.12, gerichte hoofdsite-tests | Beide **87 geslaagd, 870 assertions**: PublicPages (inclusief gated link), PublicAccess, ContactForm, ContactDelivery, StudioMail. In-memory SQLite, arraycache/-sessies, fictieve queues en fake/arraytransport. Tijdelijke views/config/packagecaches/logpad; geen bestaande contactqueue |
| Demo PHP 8.4.15 | **67 bestanden** syntax; bestaande testoutput **64 domeincontroles** en **608 formulier-/dashboardcontroles** geslaagd |
| Demo PHP 8.5.12 via eigen localhostserver | GET rules/policy/programs/seed 200, seed100; POST dates200, onvolledige roster/quote/complete422 en change400. Geen 500, JSON leesbaar, geen cookies. Private paden/oud API404, query400, verkeerde methode405. Dit is een gerichte compatibiliteitsproef, **geen volledige positieve formulier-/mutatieregressie op 8.5** |
| Composer platformvereisten | `check-platform-reqs --lock --no-dev` geslaagd op beide lokale PHP-versies. Geen dependencies bijgewerkt |
| PHP-opmaak/syntax | Pint voor de vijf betrokken PHP-bestanden geslaagd; syntax op beide runtimes |
| Frontend | Hoofdsite typecontrole, gerichte Prettier en productiebuild geslaagd. Demo typecontrole, ESLint en productiebuild geslaagd |
| Demo-isolatie | **65 PHP- en 73 frontendbestanden** geslaagd. Uitsluitend de exacte, met buildflag afgeschermde terug-anchor is als navigatie toegestaan; andere externe URL's, fetch buiten demoSession, opslag/mailconnectors blijven geweigerd |
| Nieuwe scripts/configcontract | Node-syntax; renderer weigert onbeoordeelde, onvolledige, interne en bestaande output; koppeling negen routerroutes ↔ Nginx, vaste FastCGI-entrypoint, chroot, workerqueue en timer gecontroleerd |
| SQLite-back-up en mailmonitor | Fictieve SQLite-fixture: consistente backup + quick_check; bestaande bestemming geweigerd. Apart met een nog open verbinding en actieve WAL bevestigd dat de snapshot de gecommitteerde fixture bevat. Countcontrole signaleert uncertain en failed contactjob met exit2, zonder inhoud. Geen echte database gelezen |
| Volledige verpakking | `prepare.mjs` uitgevoerd in aparte tijdelijke Git-repository met baseline plus beoordeelde runtimewijzigingen. Locked Composer-install (99 productionpackages), pnpm frozen install, npm ci en beide builds geslaagd. Minimale `site.tar.gz`, `demo.tar.gz` en SHA-256-bestandsmanifest geproduceerd; geen echte env/data/storage |
| Browser 390 × 844 / 1440 × 1000 | Werk→case, knopbestemming/eigen tabblad, toetsenbordfocus, geen horizontale overflow, formulier→dashboard binnen hetzelfde document,100 records, teruglink/eigen tabblad. Geen JS-excepties of externe HTTP-requests; alleen loopback en ingebedde data-URL's. Daarna gewone demo opnieuw gebouwd met flag false en afwezigheid van teruglink in gebouwd JavaScript gecontroleerd |
| Beeldbeoordeling | Vier screenshots daadwerkelijk geopend: case met knop/focus en demo-teruglink op beide breedtes. Originele logo/tekst/layout behouden |
| VPS / DNS | Alleen-lezen SSH-inventaris en publieke DNS: concrete versies/adressen, vrije roots en bestaande sites vastgelegd in runbook. Root-only certificaat-/vhostinformatie blijft beperkt door interactieve sudo |

De eerste lokale testpoging had een Windows-cachepadfout in de tijdelijke helper; die is hersteld naar paden relatief aan de applicatiebasis. Een mailtest meldde daarna een fout bij constructie van de fake-failover omdat de test-MAIL_URL een lege string was. Met expliciet `null` en een geïsoleerd testprofiel slagen alle 87 tests op beide versies. Geen applicatie-mailcode aangepast. De oorspronkelijke demo-domeintest weigert 8.5 wegens zijn bewuste 8.4.15-pin; die controle is **niet versoepeld**, de HTTP-proef is afzonderlijk uitgevoerd.

De nieuwe teruglink werd aanvankelijk terecht tegengehouden door de strenge bron-isolatiecheck; de uitzondering is beperkt tot precies één gated gewone anchor. Geen algemene vrijstelling voor kevinwebstudio.nl of externe requests. De tijdelijke previewserver is met zijn correcte `public`-werkdirectory opnieuw gestart; geen productie-routerwijziging nodig.

Buildmeldingen: bestaande optionele `fontaine`/plugintimingmeldingen; npm meldt de vastgelegde ESLint-versie als niet langer ondersteund en blokkeert de esbuild-installscript volgens bestaand beleid. De locked build slaagt; geen upgrade of verruiming uitgevoerd. Deze meldingen zijn geen bewezen runtimefout of volledige securityaudit.

## Grenzen en bewaarplaats bewijs

Nginx, PHP-FPM en systemd zijn lokaal niet beschikbaar (geen geïnstalleerde Linux/WSL-omgeving). **Geen native `nginx -t`, FPM-configtest of systemd-unitverify uitgevoerd**, geen actief chroot/socket-/rechtenbewijs. Die expliciete opdrachten staan in het runbook vóór latere activering. Lokale PHP 8.5.12 is ook niet de precieze Linux 8.5.4-runtime. HTTPS/DNS-omzetting, werkelijke mailontvangst/Reply-To/SPF-DKIM-DMARC, certificaatvernieuwing en publicatierechten zijn nog open.

Helpers, officiële PHP 8.4.15-runtime, fictieve databases, bouwkopieën, screenshots en `browser-results.json` staan uitsluitend onder `%TEMP%/kevin-release-13a-20261024`. Screenshots heten `case-390.png`, `case-1440.png`, `demo-back-390.png`, `demo-back-1440.png`. Ze zijn geen publicatieassets. Eigen tijdelijke testservers/Chrome zijn na de controles gestopt. De testklok van die browsertest is 2031-10-06; productieconfig bevat geen DEMO_TODAY. Alleen de bruikbare lokale demo-preview blijft draaien op **http://127.0.0.1:8120/#/formulier** en **http://127.0.0.1:8120/#/aanvragen**, met de echte klok en publieke links uit. De seedrespons met100 records is opnieuw gecontroleerd.

De volledige `git diff --check` signaleerde een al bestaande extra lege eindregel in README.md. Die wijziging is niet van deze run en blijft intact. De gerichte/staged controle moet uitsluitend de beoordeelde bestanden van deze run bevatten; README, het lokale ontwikkelrunbook en ongetrackte opdrachtbestanden blijven erbuiten.
