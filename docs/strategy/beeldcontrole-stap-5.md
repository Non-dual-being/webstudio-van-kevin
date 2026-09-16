# Resterende beeldcontrole: stap 5

Opnamen: 15 september 2026. Verslag: 16 september 2026. Gecontroleerde broncode: `c96f0107cbc5ce606e9b840397a2ef18768178b0` op `feature/public-visual-refinement`. De applicatiecode, inhoud, routes en vormgeving zijn tijdens deze taak niet gewijzigd.

## Opzet

De zes nog openstaande pagina's zijn op 1440 × 1000 en 390 × 844 pixels geladen in een eigen Chrome-profiel. De bestaande Laravel-preview op poort 8000 en Vite op poort 5174 zijn gebruikt met de bestaande projectconfiguratie. Hiervoor is geen nieuwe Vite-configuratie, build of applicatietest nodig geweest.

Helpers, het browserprofiel en ruwe browsermetingen staan buiten het project onder de tijdelijke map `kevin-webstudio-step5-65eabc9b5cf51`. De controle heeft geen formulieren ingevuld of e-mail geactiveerd. Er zijn geen andere projectmappen of productiegegevens onderzocht.

## Automatische browsermetingen

De browserrun van 15 september 2026, gestart om 14:52 UTC, is geslaagd op alle twaalf pagina/breedte-combinaties.

| Controle                                 | Resultaat                                                                                     |
| ---------------------------------------- | --------------------------------------------------------------------------------------------- |
| Horizontale documentoverloop             | Geen op 390 of 1440 px                                                                        |
| Afgekapt tekstelement volgens DOM-meting | Geen bij koppen, alinea's en links                                                            |
| Koppen en documenttitel                  | Precies één h1 en één passende unieke titel met Kevin Webstudio; geen overgeslagen kopniveaus |
| Links                                    | Bestemmingen passen bij de bestaande publieke routes; geen demoknop                           |
| Toetsenbordfocus                         | Echte Tab-toetsaanslagen langs zichtbare links; focus-visible met een zichtbare 2px-rand      |
| Merkbeeld                                | Goedgekeurde SVG geladen, 44 × 44 px, decoratief naast het woordmerk                          |
| Runtime                                  | Geen JavaScript-, console- of HTTP-fouten                                                     |

Op lange desktop-pagina's is de gemeten documentbreedte 1425 px bij een viewport van 1440 px door de scrollbar. Op korte desktop-pagina's is dit 1440 px; mobiel 390 px. Geen van deze metingen wijst op horizontale overloop.

Deze metingen geven informatie over afmetingen, structuur en bediening. Ze zijn geen bewijs dat beelden daadwerkelijk visueel zijn bekeken.

## Daadwerkelijke beeldbeoordeling

De lokale imageviewer kon de nieuwe screenshots niet openen: `helper_unknown_error: setup refresh had errors`. Dit is een omgevingsprobleem. De beelden zijn daarom nog niet als visueel goedgekeurd aangemerkt.

Na het vastleggen en pushen van de toegestane publieke screenshots wordt geprobeerd ze via de GitHub-verbinding werkelijk te bekijken. Daarna wordt dit onderdeel met de feitelijke resultaten aangevuld. Er worden geen grote base64-afbeeldingen in de terminal geprint.

Er is op basis van alleen automatische metingen geen visuele tekortkoming of ontwerpwijziging verondersteld. Leesbaarheid, tekstafbreking en uitlijning moeten op de beelden worden beoordeeld.

## Screenshots

De twaalf nieuwe beelden tonen uitsluitend de publieke Werk-, case- en dienstenpagina's in een browser zonder aangemelde gebruiker. De case bevat toelichtende tekst; er worden geen aanvragen, klantdocumenten of beelden uit een andere projectmap weergegeven.

| Pagina                  | Route                      | 390 px                                                  | 1440 px                                                   |
| ----------------------- | -------------------------- | ------------------------------------------------------- | --------------------------------------------------------- |
| Werk                    | `/werk`                    | [Mobiel](screenshots/stap-5/werk-390.png)               | [Desktop](screenshots/stap-5/werk-1440.png)               |
| Onderwijsformulier-case | `/werk/onderwijsformulier` | [Mobiel](screenshots/stap-5/onderwijsformulier-390.png) | [Desktop](screenshots/stap-5/onderwijsformulier-1440.png) |
| Diensten                | `/diensten`                | [Mobiel](screenshots/stap-5/diensten-390.png)           | [Desktop](screenshots/stap-5/diensten-1440.png)           |
| Websites                | `/diensten/websites`       | [Mobiel](screenshots/stap-5/websites-390.png)           | [Desktop](screenshots/stap-5/websites-1440.png)           |
| Dashboards              | `/diensten/dashboards`     | [Mobiel](screenshots/stap-5/dashboards-390.png)         | [Desktop](screenshots/stap-5/dashboards-1440.png)         |
| Webshops                | `/diensten/webshops`       | [Mobiel](screenshots/stap-5/webshops-390.png)           | [Desktop](screenshots/stap-5/webshops-1440.png)           |

## Afbakening

Deze taak levert een intern dienstenvoorstel en documentatie van de resterende beeldcontrole. Het echte contactformulier en de interactieve onderwijsdemo blijven afzonderlijk te bouwen releasevoorwaarden. Er zijn geen publieke tekst-, stijl- of functionaliteitswijzigingen gedaan. Er is niets gemerged of gedeployd.

Volledige applicatietests zijn niet opnieuw uitgevoerd voor uitsluitend documentatie en screenshots. De documentatie, lokale links, bedoelde bestandslijst en zowel `git diff --check` als `git diff --cached --check` worden vóór de commit gecontroleerd.
