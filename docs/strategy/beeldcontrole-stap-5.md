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

De twaalf nieuwe screenshots zijn op 16 september 2026 daadwerkelijk als beeld bekeken, op 390 en 1440 px. De lokale imageviewer bleef falen met `helper_unknown_error: setup refresh had errors`. Na de push van `8f40c5784ec4652131b883d0cfda19593ebf51f1` zijn precies de screenshots uit die commit via de GitHub-verbinding geopend. De twaalf Git-blobhashes zijn vergeleken met de lokale commit. Er zijn geen oude beelden als actuele controle gebruikt en geen grote base64-afbeeldingen in de terminal geprint.

| Pagina                  | Visueel bekeken op 390 px                                                                                                             | Visueel bekeken op 1440 px                                                                                                  |
| ----------------------- | ------------------------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------------- |
| Werk                    | Projectkaart en categorieën staan leesbaar onder elkaar; ook de lange categoriekop past. Contactblok en knop blijven binnen de pagina | Projectkaart, tweekolomsgrid en afsluitend blok zijn op de bestaande linkerlijn uitgelijnd; geen zichtbare afkapping        |
| Onderwijsformulier-case | De lange paginatitel past. Processtappen en functiekaarten stapelen rustig; tekst, technieklabels en contactactie blijven leesbaar    | Vier proceskolommen en twee kolommen met functiekaarten blijven overzichtelijk; koppen, tekst en kaartafstanden sluiten aan |
| Diensten                | De hoofdkop breekt in twee regels; kaartteksten en lange dienstnamen passen, met duidelijke ruimte rond de acties                     | Kaarten staan in twee kolommen; scheidingslijnen en acties zijn per rij gelijk uitgelijnd                                   |
| Websites                | De hoofdkop breekt in twee regels; intro, toelichtingsblok en knop hebben voldoende ruimte                                            | De kop past op één regel; leesbreedte en uitlijning van het toelichtingsblok zijn rustig                                    |
| Dashboards              | De hoofdkop gebruikt drie regels zonder afkapping; ook de lange knoptekst past                                                        | De hoofdkop gebruikt twee regels; intro, toelichting en knop volgen dezelfde linkerlijn                                     |
| Webshops                | De hoofdkop breekt in twee regels; langere alinea's en contactknop blijven binnen de tekstkolom                                       | Goed leesbare tekstbreedte, consistente uitlijning en voldoende afstand tussen intro, toelichting en knop                   |

Op deze twaalf beelden zijn geen concrete visuele tekortkomingen gevonden die een gerichte fix vragen: geen zichtbare overlap, horizontale overloop of afgekapt tekstblok. De beeldcontrole is daarmee afgerond voor de twee gevraagde breedtes. Er is geen nieuw ontwerp of codewijziging uitgevoerd.

De automatische browsermetingen blijven afzonderlijke resultaten. De beeldbeoordeling is gebaseerd op echte screenshots; zij vervangt geen test op een fysieke telefoon of controle van andere schermbreedtes.

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

Volledige applicatietests zijn niet opnieuw uitgevoerd voor uitsluitend documentatie en screenshots. De twee nieuwe Markdownbestanden zijn met Prettier gecontroleerd; de relatieve links, vijf gebundelde beslisvragen en bedoelde bestandslijst zijn gecontroleerd. Zowel `git diff --check` als `git diff --cached --check` zijn geslaagd. Na deze beeldbeoordeling zijn uitsluitend dit verslag en het open controlepunt in het stap-4-verslag bijgewerkt.

De eigen tijdelijke Chrome-browser is na vergelijking van PID en profiel afgesloten. De bestaande Laravel- en Vite-processen zijn niet gestopt.
