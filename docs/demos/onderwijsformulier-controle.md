# Controle: Onderwijsformulier

De portfolio-case is lokaal afgerond op 11 september 2026. De interactieve demo is niet gebouwd.

## Resultaat

- GET /werk/onderwijsformulier is publiek toegankelijk en rendert Work/Show.
- Home en Werk ontvangen dezelfde centrale projectgegevens uit config/projects.php en gebruiken ProjectCard met de gegenereerde Wayfinder-link.
- De frontend herkent Work/Show als publieke pagina. De bestaande PublicLayout, BrandMark, PublicButtonLink en PublicCardAction zijn hergebruikt en in deze run niet gewijzigd.
- De case beschrijft uitdaging, oplossing, functies, proces, stack en onderscheid met de interne planner. Contact verwijst naar de bestaande /contact-pagina.
- Geen demo-knop, screenshots van de bronapp, klantgegevens, quotes of claims over persoonlijke bijdrage of bewezen besparing.
- Brononderzoek en concreet demo-advies staan in [onderwijsformulier.md](onderwijsformulier.md); de volledige teksten in [config/projects.php](../../config/projects.php).

## Gewijzigd of toegevoegd in deze run

- `app/Http/Controllers/PortfolioController.php`
- `config/projects.php`
- `resources/js/app.ts`
- `resources/js/types/portfolio.ts`
- `resources/js/components/public/ProjectCard.vue`
- `resources/js/pages/Home.vue`
- `resources/js/pages/Work/Index.vue`
- `resources/js/pages/Work/Show.vue`
- `routes/web.php`
- `tests/Feature/PublicPagesTest.php`
- `docs/demos/onderwijsformulier.md`
- `docs/demos/browsercontrole.json`
- `docs/demos/onderwijsformulier-controle.md`
- `docs/demos/screenshots/home-1440.png`
- `docs/demos/screenshots/home-390.png`
- `docs/demos/screenshots/werk-1440.png`
- `docs/demos/screenshots/werk-390.png`
- `docs/demos/screenshots/onderwijsformulier-1440.png`
- `docs/demos/screenshots/onderwijsformulier-390.png`

Home en Work/Index hadden al wijzigingen vóór deze run. Die vormden het uitgangspunt. Andere reeds aanwezige wijzigingen aan de publieke stijl, het logo, knoppen en pagina's zijn behouden.

## Controles

| Controle          | Resultaat                                                                                 |
| ----------------- | ----------------------------------------------------------------------------------------- |
| Build / Wayfinder | Geslaagd met PHP 8.4.15 in PATH; definitieve Vite-build vóór de definitieve Laravel-tests |
| Laravel-tests     | 103 geslaagd, 443 assertions                                                              |
| Publieke routes   | Bestaande negen pagina's en de nieuwe case via PublicPagesTest                            |
| Casegegevens      | Work/Show, gast zonder gebruiker, centrale titel/gegevens, geen demoUrl                   |
| Onbekende slugs   | 404 voor /werk/onbekend-project en /werk/onderwijsformulier.title                         |
| ESLint            | pnpm lint:check geslaagd                                                                  |
| Formatter         | pnpm format:check geslaagd; nieuwe Markdown en JSON eveneens geformatteerd                |
| TypeScript        | pnpm types:check geslaagd                                                                 |
| PHP-stijl         | PHP 8.4.15 vendor/bin/pint --test geslaagd                                                |
| PHP-analyse       | PHP 8.4.15 vendor/bin/phpstan analyse --memory-limit=512M geslaagd, 0 fouten              |
| Browser           | Chrome headless, desktop 1440 × 1000 en mobiel 390 × 844                                  |
| Navigatie         | Home → case, Werk → case, case → Contact via Inertia op beide breedtes                    |
| Toetsenbord       | Zichtbare focus op de caselink met Tab en Shift+Tab                                       |
| Layout            | Eén h1 per pagina, geen horizontale overloop, logo geladen, geen demo-actie               |
| Browserfouten     | Geen console-/runtime-/HTTP-fouten; alle assets lokaal                                    |
| git diff --check  | Geslaagd                                                                                  |

De build gaf een melding over het optionele pakket fontaine voor geoptimaliseerde fallbackfonts. Er zijn geen packages toegevoegd. De melding blokkeert de build niet. De gebruikelijke plugin-timingmelding was eveneens niet blokkerend.

Tijdens het opzetten van de controle liep ESLint eerst tegen een tijdelijke Vite-wrapper buiten de TS-projectconfiguratie aan. Na het verwijderen daarvan slaagde de volledige controle. De tijdelijke PHP-server moest vanuit public worden gestart; de browserasserties zijn afgestemd op het bestaande Contact-opschrift en echte toetsenbordinvoer. Deze problemen zijn opgelost vóór de definitieve browsercontrole.

## Testisolatie

De controle gebruikte een nieuw tijdelijk .env.testing-bestand met uitsluitend synthetische instellingen. APP_ENV=testing, een tijdelijke APP_KEY, DB_CONNECTION=sqlite, DB_DATABASE=:memory:, SESSION_DRIVER=array, CACHE_STORE=array, MAIL_MAILER=array en LOG_CHANNEL=null zijn expliciet ingesteld. APP_CONFIG_CACHE verwees naar een afzonderlijke niet-bestaande testcache.

De build gebruikte pnpm build met een tijdelijke Vite-config die de bestaande vite.config.ts importeerde en envDir op een lege testmap zette. Daardoor las Vite geen productie-.env. Wayfinder gebruikte dezelfde PHP 8.4.15 en geïsoleerde Laravel-omgeving. De browser sprak alleen de tijdelijke localhost-server aan.

Na afloop zijn de server, tijdelijke configuratie, testomgeving en eigen browsercache opgeruimd. De gegenereerde public/build-bestanden zijn beschikbaar en blijven volgens de bestaande .gitignore buiten git.

## Screenshots

Alle zes PNG's zijn daadwerkelijk vanuit de nieuwe portfolio-pagina's gemaakt en visueel bekeken. Dit zijn controlescreenshots van Kevin Webstudio, geen productafbeeldingen voor de case.

| Pagina             | Desktop                                            | Mobiel                                           |
| ------------------ | -------------------------------------------------- | ------------------------------------------------ |
| Home               | [1440 px](screenshots/home-1440.png)               | [390 px](screenshots/home-390.png)               |
| Werk               | [1440 px](screenshots/werk-1440.png)               | [390 px](screenshots/werk-390.png)               |
| Onderwijsformulier | [1440 px](screenshots/onderwijsformulier-1440.png) | [390 px](screenshots/onderwijsformulier-390.png) |

Machinale browseruitkomsten: [browsercontrole.json](browsercontrole.json). De controle gebruikte mobiele viewportemulatie, geen fysieke telefoon. De PNG's zijn onbewerkt bewaard; voor visuele inspectie zijn ze in geheugen naar JPEG omgezet omdat de ingebouwde bestandsviewer door een sandboxstoring niet werkte.

## Git en scope

GeoFortForm4 had bij aanvang een lege git status --short en heeft die na afloop nog steeds. Ook git diff --stat is daar leeg. In dat project zijn uitsluitend leesacties uitgevoerd.

Geen productie-.env, databasebestanden, logs of echte aanvragen uit de bronapp gelezen. Geen commit, push, deployment of nieuwe packages. De portfolio-case kan lokaal worden bekeken; de demo is uitsluitend voorgesteld.

De onderstaande status bevat ook de reeds aanwezige wijzigingen. git diff --stat vergelijkt gevolgde bestanden met HEAD en telt nieuwe, nog niet gevolgde bestanden niet mee; de lijst hierboven geeft de taakbestanden inclusief nieuwe bestanden en screenshots.

### Git status --short na opruimen

```text
 M resources/js/app.ts
 M resources/js/layouts/PublicLayout.vue
 M resources/js/pages/About.vue
 M resources/js/pages/Contact.vue
 M resources/js/pages/Home.vue
 M resources/js/pages/Privacy.vue
 M resources/js/pages/Services/Dashboards.vue
 M resources/js/pages/Services/Index.vue
 M resources/js/pages/Services/Webshops.vue
 M resources/js/pages/Services/Websites.vue
 M resources/js/pages/Work/Index.vue
 M routes/web.php
 M tests/Feature/PublicPagesTest.php
?? app/Http/Controllers/PortfolioController.php
?? config/projects.php
?? docs/brand/
?? docs/demos/
?? public/brand/
?? resources/js/components/public/
?? resources/js/pages/Work/Show.vue
?? resources/js/types/portfolio.ts
```

### Git diff --stat

```text
 resources/js/app.ts                        |   1 +
 resources/js/layouts/PublicLayout.vue      |   6 +-
 resources/js/pages/About.vue               |  11 ++-
 resources/js/pages/Contact.vue             | 142 +++++++++++++++++++++++++----
 resources/js/pages/Home.vue                | 129 ++++++++++++++++----------
 resources/js/pages/Privacy.vue             |  11 ++-
 resources/js/pages/Services/Dashboards.vue |  11 ++-
 resources/js/pages/Services/Index.vue      |  41 +++++----
 resources/js/pages/Services/Webshops.vue   |  11 ++-
 resources/js/pages/Services/Websites.vue   |  11 ++-
 resources/js/pages/Work/Index.vue          |  59 ++++++++----
 routes/web.php                             |   6 +-
 tests/Feature/PublicPagesTest.php          |  35 +++++++
 13 files changed, 337 insertions(+), 137 deletions(-)
```
