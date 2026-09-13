# Ontwerpkeuzes

## Officiële logobron

public/brand/kevin-webstudio-kds-mark.svg is de bron van waarheid voor het KDS-merkteken. De header laadt dit bestand via BrandMark.vue als decoratieve afbeelding naast de zichtbare naam Kevin Webstudio. Alleen de viewBox is verkleind om overtollige transparante marge weg te nemen; de paden en daarmee de geometrie zijn ongewijzigd. De bijbehorende PNG is een referentie-export en geen aparte logobron.

## Favicons

De favicon-SVG neemt de oorspronkelijke paden en viewBox van het KDS-merkteken over. Een licht achtergrondvlak (#FAFAF9) maakt de donkere lijnen ook op donkere browserachtergronden zichtbaar. favicon.ico bevat exports van 16, 32 en 48 px; apple-touch-icon.png is een export van 180 px met een volledig lichte achtergrond. De originele PNG en de transparante SVG voor de header blijven ongewijzigd.

## Knopvarianten

- De primaire knop heeft een donkerblauwe achtergrond met witte tekst. Bij hover verandert alleen de achtergrond in ongeveer 160 ms naar een iets lichtere blauwtint.
- De omlijnde secundaire knop vult in ongeveer 220 ms van links naar rechts met donkerblauw. De donkerblauwe opvulling en witte tekst staan in één gezamenlijk afgesneden vlak, zodat tekst en achtergrond tijdens de hele overgang gelijk blijven lopen zonder de afmetingen te veranderen.
- Neem contact op gebruikt dezelfde primaire variant. Er is geen aparte oranje contactvariant.
- Beide varianten behouden een duidelijke focus-visible-rand. Bij prefers-reduced-motion: reduce worden de overgangen zonder animatie toegepast.

## Contactopzet

De Contact-pagina heeft een persoonlijke introductie naast het zachtgrijze kader Dit helpt bij een eerste gesprek. Dat kader vraagt naar doel, gebruikers, huidige knelpunten en planning. Daaronder staan de drie stappen kennismaken, voorstel afstemmen en bouwen en bijstellen.

## Zakelijk contactadres

In stap 4 is het aangemaakte zakelijke adres `info@kevinwebstudio.nl` door Kevin bevestigd. De Contact-pagina toont dit als gewone `mailto:`-link, met de bestaande publieke linkkleuren en toetsenbordfocus. Hiermee opent de bezoeker zijn eigen mailprogramma. De technische bezorging is in deze stap niet getest. Er is nog geen contactformulier of verzendbackend gebouwd.

Voor de eerste release is een echt werkend contactformulier verplicht, naast de interactieve onderwijsformulier-demo. Beide worden in afzonderlijke stappen gebouwd. Zie [positionering en tekstbasis](../strategy/positionering.md) en de [controle van stap 3](merkafwerking-stap-3.md).
