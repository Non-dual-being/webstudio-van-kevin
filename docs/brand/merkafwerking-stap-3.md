# Merkafwerking — stap 3

Datum: 12 september 2026. Basis: `d68551b` op `feature/public-visual-refinement`.

## Behouden

Het originele KDS-merkbeeld, de header met Home-link, het woordmerk zonder onderlijning, de navigatiestatussen, grids, routes en pagina-inhoud zijn behouden. `BrandMark.vue` gebruikt al de goedgekeurde SVG: 44 × 44 px, met circa 39 px zichtbaar beeld. De vierkante verhouding en transparante marge passen in de header. De oorspronkelijke PNG en SVG zijn ongewijzigd.

Het beeld in de header is decoratief (`alt=""`); de zichtbare studionaam geeft de link één toegankelijke naam. Ook de extra tekstlaag van de secundaire knop is verborgen voor hulptechnologie. Beide namen zijn gecontroleerd in de toegankelijkheidsboom van Chrome.

De normale primaire en secundaire knopweergave, focusrand en subtiele primaire hover blijven behouden. Contactknoppen gebruiken dezelfde primaire variant.

## Gewijzigd

- De secundaire knop onthult achtergrond en witte tekst nu binnen één gezamenlijk afgesneden vlak. Zo lopen beide grenzen gelijk tijdens de opvulling van links naar rechts; de eerdere afzonderlijke animaties hadden verschillende breedtes.
- De Laravel-favicons zijn vervangen door afgeleiden van het bestaande KDS-beeldmerk: SVG, ICO met 16/32/48 px en een Apple-touch-icon van 180 px. De oorspronkelijke SVG-geometrie is overgenomen. Een licht vlak achter het icoon houdt de donkere lijnen zichtbaar op lichte en donkere browserachtergronden.
- `docs/strategy/positionering.md` is als bedoelde toevoeging opgenomen, zonder tekstwijzigingen of toepassing op de pagina's.

## Controles

Uitgevoerd met WAMP PHP 8.4.15 vooraan in het terminal-PATH, ook bij de Vite/Wayfinder-build. pnpm 11.5.2; geen globale instellingen aangepast.

| Controle | Resultaat |
| --- | --- |
| `pnpm lint:check` | Geslaagd |
| `pnpm format:check` | Geslaagd |
| `pnpm types:check` | Geslaagd |
| `pnpm build` | Geslaagd; bestaande melding over optionele Fontaine-ondersteuning en plugintiming is niet blokkerend |
| `git diff --check` | Geslaagd |
| Home, Werk, Onderwijsformulier, Diensten en Contact | Visueel gecontroleerd bij 390 en 1440 px viewportbreedte; geen horizontale overflow |
| Hover en overgang | Normale maten behouden; ook echte muishover verandert posities of afmetingen niet |
| Tekstcontrast secundaire knop | Circa 17,1:1 normaal en 17,8:1 op de donkere opvulling |
| Toetsenbord | Zichtbare focus op merklink, navigatie en knoppen; Tab en Enter werken |
| Minder beweging | De opvulling verschijnt direct, met 0 s overgang |
| Aanraakbediening | Navigatie via een nagebootste aanraking geslaagd |
| Browserfouten | Geen console-, JavaScript- of HTTP-fouten waargenomen |

Preview via `php artisan serve --host=127.0.0.1 --port=8000` met de bestaande projectconfiguratie en gebouwde assets. Geen tijdelijke Vite-configuratie gebruikt. Hulpscripts, browserprofiel en logs staan buiten de projectmap in de tijdelijke map `kevin-webstudio-brand-step3`.

Beperking: de mobiele controle is Chrome-emulatie, geen test op een fysiek toestel. CDP liet tijdens een vastgehouden aanraking geen betrouwbare native `:active`-toestand zien. De actieve CSS-stijl is daarom afzonderlijk geforceerd en visueel gecontroleerd. Hoverbeelden gebruiken eveneens een geforceerde CSS-toestand; de tussentoestand is gepauzeerd voor inspectie. Echte muishover en aanraaknavigatie zijn daarnaast apart gecontroleerd.

## Screenshots

Alle beelden tonen uitsluitend de publieke portfolio-inhoud of het goedgekeurde merkbeeld, zonder echte aanvragen of beheergegevens.

| Pagina | Desktop | Mobiel |
| --- | --- | --- |
| Home | [1440 px](screenshots/stap-3/home-1440.png) | [390 px](screenshots/stap-3/home-390.png) |
| Werk | [1440 px](screenshots/stap-3/werk-1440.png) | [390 px](screenshots/stap-3/werk-390.png) |
| Onderwijsformulier | [1440 px](screenshots/stap-3/onderwijsformulier-1440.png) | [390 px](screenshots/stap-3/onderwijsformulier-390.png) |
| Diensten | [1440 px](screenshots/stap-3/diensten-1440.png) | [390 px](screenshots/stap-3/diensten-390.png) |
| Contact | [1440 px](screenshots/stap-3/contact-1440.png) | [390 px](screenshots/stap-3/contact-390.png) |

- [Merkbeeld en iconen](screenshots/stap-3/merkbeeld.png)
- [Primaire hover](screenshots/stap-3/primary-hover-1440.png)
- [Secundaire knop tijdens opvulling](screenshots/stap-3/secondary-transition-1440.png)
- [Secundaire hover](screenshots/stap-3/secondary-hover-1440.png)
- [Focus op merklink](screenshots/stap-3/brand-focus-1440.png)
- [Toetsenbordfocus op knop](screenshots/stap-3/keyboard-focus-1440.png)
- [Minder beweging](screenshots/stap-3/reduced-motion-1440.png)
- [Actieve stijl op mobiel, geforceerde CSS-toestand](screenshots/stap-3/touch-active-style-390.png)

## Vervolg

Het echte contactformulier en de interactieve onderwijsformulier-demo blijven verplicht voor de eerste release en worden in afzonderlijke stappen gebouwd. Deze afwerking voegt geen demo-claims of demoknoppen toe. De positioneringsteksten worden pas in stap 4 toegepast.

Authenticatie, CI, SSR, database- en serverconfiguratie zijn niet gewijzigd. GeoFortForm4 is ongewijzigd. Merge en deployment volgen afzonderlijk.
