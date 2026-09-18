# Controle publieke aanbodteksten: stap 5

Datum: 18 september 2026. Branch: `feature/public-visual-refinement`. Vertrekpunt: `9fb4e85`; bij controle gelijk aan origin na fetch.

## Toegepaste inhoud

Gewijzigd: Home, Diensten en de bestaande detailpagina’s Websites, Webapplicaties op maat en Webshops en productcatalogi. Onderhoud en doorontwikkeling staat in de vierde kaart op Home en Diensten, met op Diensten een directe contactactie. De URL voor webapplicaties blijft `/diensten/dashboards`.

De vier aanbiedingen beschrijven de klantvraag, voorbeelden en onderdelen die tijdens een kennismaking worden afgesproken. Diensten en de detailpagina’s gebruiken “Prijs na kennismaking”. Zelf teksten en beelden beheren is een vooraf af te spreken optie; catalogusaanvragen zijn onderscheiden van daadwerkelijk online bestellen.

De vormgeving, navigatie, routes, centrale onderwijsformulier-case en gewone mailto-link blijven behouden. Er is geen CMS, webshop, contactformulier, mailbackend of demo gebouwd. Het [interne voorstel](diensten-en-pakketten.md) onderscheidt de toegepaste teksten van nog onbevestigde commerciële keuzes.

## Technische controles

Alle commando’s zijn uitgevoerd met `C:\wamp64\bin\php\php8.4.15` vooraan in PATH; `php --version` bevestigt PHP 8.4.15.

| Controle                                           | Resultaat                                                                  |
| -------------------------------------------------- | -------------------------------------------------------------------------- |
| `pnpm format:check`                                | Geslaagd                                                                   |
| `pnpm lint:check`                                  | Geslaagd                                                                   |
| `pnpm types:check`                                 | Geslaagd                                                                   |
| Prettier op de twee gewijzigde strategiedocumenten | Geslaagd                                                                   |
| `composer validate`                                | Geslaagd                                                                   |
| `vendor/bin/pint --test`                           | Geslaagd                                                                   |
| `vendor/bin/phpstan analyse --memory-limit=512M`   | Geslaagd, geen fouten                                                      |
| `pnpm build`                                       | Geslaagd                                                                   |
| `php artisan test`, na de build                    | 103 tests geslaagd, 443 assertions; inclusief de bestaande PublicPagesTest |
| `git diff --check`                                 | Geslaagd                                                                   |

PublicPagesTest controleert de bestaande publieke routes, de centrale casegegevens op Home en Werk, de onderwijsformulier-case zonder demo-URL en onbekende cases. Er zijn geen nieuwe applicatietests toegevoegd voor deze tekstwijziging.

De build meldt dat de optionele dependency `fontaine` ontbreekt voor geoptimaliseerde fontfallbacks en geeft een informatieve melding over plugintijden. De build slaagt; dependencies en Vite-instellingen zijn niet aangepast.

## Browsercontrole

De vijf gewijzigde pagina’s zijn na de build gecontroleerd in een eigen Chrome-profiel op **390 × 844** en **1440 × 1000** pixels, met de lokale Laravel-preview op poort 8000. De browser laadt de gebouwde bestanden onder `/build/assets/`; er is geen Vite-devserver gebruikt. De screenshots tonen de volledige pagina’s, die langer kunnen zijn dan de viewport.

| Controle                                                   | Resultaat                                                                                                 |
| ---------------------------------------------------------- | --------------------------------------------------------------------------------------------------------- |
| Documentbreedte en afkapping van koppen, alinea’s en links | Geen horizontale overloop of gemeten afkapping op alle tien combinaties                                   |
| Koppen en titels                                           | Eén h1, één passende unieke documenttitel en geen overgeslagen kopniveaus                                 |
| Namen en prijsvermelding                                   | Dezelfde vier dienstnamen op Home en Diensten; vier prijsvermeldingen op Diensten en één per detailpagina |
| Zelfbeheer en catalogus                                    | Optie herkenbaar op alle detailpagina’s; aanvraag en bestelling expliciet onderscheiden                   |
| Toetsenbord                                                | Tab langs de zichtbare links; focus-visible met een zichtbare 2px-rand                                    |
| Merkbeeld                                                  | Goedgekeurde decoratieve SVG geladen op 44 × 44 pixels                                                    |
| Navigatie                                                  | Home → Diensten; de drie dienstenkaarten → detailpagina → Contact; onderhoud → Contact                    |
| Contactbestemming                                          | Bestaande `mailto:info@kevinwebstudio.nl` aanwezig; geen e-mail geactiveerd of formulier verstuurd        |
| Runtime                                                    | Geen JavaScript-, console- of HTTP-fouten                                                                 |

De browsermetingen en hulpscripts staan lokaal in de tijdelijke map `kevin-webstudio-public-step5-20260918`, buiten het project.

## Daadwerkelijke beeldbeoordeling en screenshots

Alle tien nieuwe screenshots zijn vóór commit visueel bekeken. De gewone lokale imageviewer faalde met een sandbox-setupfout. Een beperkte lokale leeshelper gaf daarom dezelfde PNG-opnamen als JPEG-weergave door aan de beeldviewer, op hun oorspronkelijke afmetingen. De hieronder gekoppelde PNG-bestanden zijn de originele browseropnamen.

| Pagina                      | Visuele bevinding                                                                                                                                        | 390 px                                                     | 1440 px                                                      |
| --------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------- | ------------------------------------------------------------ |
| Home                        | Vier leesbare aanbodkaarten; op mobiel onder elkaar en op desktop in twee kolommen. De bestaande case, werkwijze en contactactie passen binnen de pagina | [Mobiel](screenshots/aanbod-stap-5/home-390.png)           | [Desktop](screenshots/aanbod-stap-5/home-1440.png)           |
| Diensten                    | Langere kaartteksten, prijsvermelding en acties passen. Desktopkaarten hebben per rij gelijk uitgelijnde voetteksten; mobiel geen overlap of afkapping   | [Mobiel](screenshots/aanbod-stap-5/diensten-390.png)       | [Desktop](screenshots/aanbod-stap-5/diensten-1440.png)       |
| Websites                    | Intro, kennismakingsafspraken, zelfbeheeroptie, prijsregel en contactknop zijn leesbaar en volgen de bestaande uitlijning                                | [Mobiel](screenshots/aanbod-stap-5/websites-390.png)       | [Desktop](screenshots/aanbod-stap-5/websites-1440.png)       |
| Webapplicaties op maat      | Nieuwe titel past op beide breedtes; formulieren en dashboards zijn herkenbaar. De toelichting en langere contactknop blijven binnen de tekstkolom       | [Mobiel](screenshots/aanbod-stap-5/webapplicaties-390.png) | [Desktop](screenshots/aanbod-stap-5/webapplicaties-1440.png) |
| Webshops en productcatalogi | Catalogus- en besteltekst, beheeroptie en contactactie passen zonder zichtbare overloop; voldoende ruimte tussen de blokken                              | [Mobiel](screenshots/aanbod-stap-5/webshops-390.png)       | [Desktop](screenshots/aanbod-stap-5/webshops-1440.png)       |

De screenshots en browsermetingen tonen geen concrete visuele tekortkomingen op deze twee breedtes. Dit is een browsercontrole, geen test op een fysieke telefoon.

## Afbakening van de commit

De bedoelde bestanden zijn de vijf Vue-pagina’s, het bijgewerkte interne voorstel, dit controleverslag en de tien screenshots. De tijdens de onderbreking aangetroffen lokale wijziging in `docs/runbooks/lokaal-ontwikkelen-en-git.md` is behouden en wordt niet meegenomen. Bouwbestanden, tijdelijke helpers en lokale configuratie worden niet toegevoegd.

Deze taak omvat geen merge of deployment.
