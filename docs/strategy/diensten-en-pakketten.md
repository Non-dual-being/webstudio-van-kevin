# Diensten en pakketten: intern voorstel voor stap 5

**Status: VOORSTEL TER BEOORDELING — niet vastgesteld en niet voor publicatie.**

Datum: 15–16 september 2026. Basis: `feature/public-visual-refinement` op `c96f0107cbc5ce606e9b840397a2ef18768178b0`, met een schone werkboom en gelijk aan origin na fetch. De herstelde teksten van stap 4 blijven behouden.

Alle hieronder genoemde inbegrepen onderdelen, aantallen, feedbackrondes en afbakeningen zijn **VOORSTEL**. Kevin heeft ze nog niet als verkoopvoorwaarden bevestigd. Dit document bevat geen definitieve prijzen, uurtarieven, oplevertermijnen, beschikbaarheidsgaranties, juridische voorwaarden of btw-uitspraken. De publieke website en applicatiefunctionaliteit zijn in deze stap niet aangepast.

## Vergelijking in één oogopslag

| Aanbod                        | Geschikt voor                                                                    | Wat de klant krijgt — VOORSTEL                                                                       | Belangrijkste grens — VOORSTEL                                                                                     | Logische prijsaanpak                                                                         |
| ----------------------------- | -------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------ | -------------------------------------------------------------------------------------------- |
| Website                       | Een ondernemer of organisatie die aanbod, werk en contact helder wil presenteren | Een ingerichte, op mobiel en desktop bruikbare website met afgesproken inhoud en contactmogelijkheid | Maximaal 5 pagina's, 1 taal, 2 gebundelde feedbackrondes                                                           | Vaste prijs zodra pagina's, inhoud, contactverwerking en beheer zijn afgebakend              |
| Webapplicatie op maat         | Een concreet aanvraag-, plannings- of beheerproces                               | Een eerste werkende versie van één afgesproken proces, met passende invoer, gegevens en controles    | 1 proces, maximaal 2 gebruikersrollen, 1 taal, 2 feedbackrondes; regels en uitzonderingen afzonderlijk beschrijven | Prijs na intake; zo nodig eerst afzonderlijk onderzoek, daarna een afgebakende bouwfase      |
| Webshop of productcatalogus   | Een organisatie die producten wil tonen, aanvragen ontvangen of online verkopen  | Een catalogus als afgebakend vertrekpunt; verkoopfuncties alleen na afzonderlijke uitwerking         | Catalogus: maximaal 20 producten invoeren, 5 categorieën, 1 producttemplate, 1 taal, 2 feedbackrondes              | Catalogus eventueel vast; webshop na intake en keuzes over bestellen, betalen en afhandeling |
| Onderhoud en doorontwikkeling | Een eigenaar die technisch beheer, hulp en verdere verbeteringen wil regelen     | Een afzonderlijk afgesproken combinatie van beheer, back-ups, ondersteuning en/of nieuw werk         | Alleen benoemde systemen en werkzaamheden; geen onbeperkte ondersteuning of automatische functiebouw               | Afzonderlijke terugkerende afspraak of losse opdracht; nieuwe functies apart begroten        |

De aantallen begrenzen het voorgestelde opleverwerk. Ze zijn geen gemeten capaciteitslimiet van de software. Vooral bij een webapplicatie bepaalt een aantal pagina's onvoldoende hoeveel werk nodig is.

## Onderbouwing en betrouwbaarheid

Gelezen: [positionering](positionering.md), [teksten en controle van stap 4](teksten-stap-4.md), [ontwerpkeuzes](../brand/ontwerpkeuzes.md), de vier bestaande dienstenpagina's, [centrale projectinformatie](../../config/projects.php) en [brononderzoek van het onderwijsformulier](../demos/onderwijsformulier.md). README en het lokale runbook zijn gevolgd voor project- en Git-controles; voor uitsluitend documentatie zijn geen volledige applicatietests herhaald.

Er zijn drie verschillende soorten uitspraken:

| Status                                       | Onderbouwde toepassing in dit voorstel                                                                                                                                 | Begrenzing                                                                                                                                                                             |
| -------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Aangetoond in dit project**                | De huidige publieke website toont responsieve informatiepagina's, dienstenkaarten, een case, navigatie en een gewone mailto-link                                       | Dit bewijst geen conversieresultaat, klantenbestand, klant-CMS of werkend contactformulier                                                                                             |
| **Vastgelegd in eerder brononderzoek**       | Het onderwijsformulier heeft conditionele vervolgkeuzes, datum- en capaciteitsregels, servervalidatie, een prijsopgave en afzonderlijke uitkomsten voor opslag en mail | Dit is codeonderzoek volgens het demodocument; geen in deze stap uitgevoerde productieboekingen of bezorgtests. Exacte persoonlijke bijdrage en klanttoestemming zijn niet vastgesteld |
| **Nieuw te bouwen binnen een opdracht**      | Een eenvoudige contactflow, een afgesproken aanvragenoverzicht, productpresentatie of een beperkte export kan onderdeel van een nieuwe opdracht zijn                   | Dit zijn voorgestelde werkzaamheden, geen reeds beschikbare modules of claims over eerder opgeleverde klantfuncties                                                                    |
| **Eerst haalbaarheid en omvang onderzoeken** | Automatisch plannen, complexe rechten, bestaande gegevens migreren, betaal- en voorraadsystemen of boekhoudkoppelingen                                                 | Platform, toegang, gegevenskwaliteit, regels, uitzonderingen en afhankelijkheden bepalen de haalbaarheid en begroting                                                                  |

Het bestaande onderwijsformulier is een bruikbaar voorbeeld van samenhangende keuzes en controles. De beschreven roosterfunctie zoekt een passend conceptrooster; dat is geen bewijs voor een algemene roostergenerator. De interne planner is niet de uitgewerkte publieke case en wordt niet als kant-en-klaar product aangeboden.

**De interactieve onderwijsformulier-demo is nog niet beschikbaar.** Het onderzoek beschrijft een toekomstige, afzonderlijke demo. Er is geen klaar herbruikbaar domeinpakket vastgesteld en geen recht op hergebruik of overdracht verondersteld. Een nieuwe klantopdracht vraagt een eigen afbakening.

Andere beoogde projecten worden hier niet als bewijs gebruikt: hun projectmappen zijn niet onderzocht. Productiedatabases, geheimen, logs en echte aanvragen zijn niet ingezien. De oudere contact- en kaartteksten onderaan het demodocument zijn ingehaald door stap 4; voor de huidige inhoud zijn `config/projects.php`, de publieke pagina's en het stap-4-verslag leidend.

## Gemeenschappelijke werkwijze — VOORSTEL

1. **Kennismaken en afbakenen.** Leg het doel, de gebruikers, het gewenste resultaat, benodigde inhoud, randvoorwaarden en open onderzoekspunten vast. Begroot pas wanneer de aannames duidelijk zijn.
2. **Afspreken wat we bouwen.** Maak een lijst van inbegrepen onderdelen, opties, uitgesloten werk en concrete voorbeelden van goed en fout gebruik. Kies één contactpersoon die feedback van de klant bundelt.
3. **Eerste versie en feedback.** VOORSTEL: twee gebundelde feedbackrondes, één op inhoud/opzet en één op de werkende versie. Iedere ronde is één afgestemde lijst binnen de afgesproken omvang. Een andere dienst, extra taal, nieuwe regel of nieuwe koppeling wordt als uitbreiding besproken.
4. **Oplevercontrole.** Loop de afgesproken bezoekers- en beheertaken door, inclusief mobiel, toetsenbord, links, invoerfouten en eventuele mail- of betaaluitkomsten. Gebruik daarvoor passende testgegevens en testomgevingen.
5. **Overdracht.** VOORSTEL: één gezamenlijke uitleg, een korte gebruiksnotitie en een overzicht van accounts, terugkerende diensten en nog open punten. Plan publicatie en eventuele nazorg afzonderlijk.

Een afwijking van de afgesproken werking wordt als herstelpunt vastgelegd en niet automatisch als nieuwe wens aangemerkt. De behandeling van herstelpunten en de periode na oplevering moeten nog worden afgesproken; hiermee wordt geen onderhoudsgarantie geïntroduceerd.

## A. Website

### Voor wie en welk probleem

Voor een ondernemer of organisatie waarvan bezoekers niet snel begrijpen wat die doet, welk werk te bekijken is of hoe ze contact kunnen opnemen. Het doel is een heldere presentatie met een logische volgende stap.

### Wat de klant krijgt en inbegrepen werk — VOORSTEL

- Een ingerichte website met maximaal **5 afzonderlijke pagina's** in **1 taal**. Een voorbeeldindeling is Home, Diensten, Werk, Over mij en Contact. Losse cases en eventuele privacy- of voorwaardenpagina's worden vooraf meegeteld; ze komen niet stilzwijgend boven op het aantal.
- Een afgestemde inhoudsstructuur en vormgeving op basis van de aangeleverde identiteit, bruikbaar op mobiel en desktop.
- Plaatsing en beperkte redactie van aangeleverde teksten en beelden; passende uitsneden en webformaten van het geselecteerde beeld.
- Paginatitels, korte beschrijvingen, begrijpelijke koppen, navigatie, afbeeldingsbeschrijvingen en controles op links en toetsenbordbediening. Er wordt geen vindbaarheidspositie of toegankelijkheidscertificering beloofd.
- **VOORSTEL:** één eenvoudige contactflow, met maximaal **5 afgesproken invoervelden** en **1 zakelijk ontvangstadres**, invoercontrole, fout-/succesweergave en een afgesproken maatregel tegen misbruik. Opslag, benodigde persoonsgegevens en e-mailafhandeling worden vóór begroting bepaald. Dit is nieuw te bouwen werk; Kevin Webstudio heeft nu alleen een mailto-link.
- **2 gebundelde feedbackrondes**, controle van de afgesproken pagina's en contactflow, en overdracht volgens de gemeenschappelijke werkwijze.

### Opties en afzonderlijk maatwerk — VOORSTEL

Extra pagina's of talen, een beheeromgeving om inhoud zelf te wijzigen, uitgebreide tekstproductie, fotografie, een nieuwe huisstijl, zoekfunctie, nieuwsbrief, afsprakenmodule, bestandsuploads, migratie van bestaande inhoud en externe koppelingen worden afzonderlijk beschreven en begroot.

### Buiten de voorgestelde omvang

Een webshop, klantportaal, uitgebreide conditionele aanvraagflow, bedrijfsplanning, advertentiecampagnes en doorlopend contentbeheer vallen buiten dit websitevoorstel. Een CMS en een blog zijn niet automatisch inbegrepen. Een afgeronde website brengt niet vanzelf een onderhoudsabonnement mee.

### Aanlevering door de klant

Een vastgesteld aanbod en doel, de pagina-indeling, goedgekeurde teksten, contactgegevens, logo en beelden met toegestane gebruiksrechten. Ook benodigd: keuze van contactafhandeling, wie inhoud later bijhoudt, de gewenste domeinnaam en passende toegang tot bestaande domein-/hostingaccounts. Benodigde verklaringen en voorwaarden worden inhoudelijk door de klant aangeleverd of afzonderlijk geregeld.

### Feedback, oplevering en overdracht

De eerste ronde beoordeelt structuur en inhoud, de tweede de ingerichte website. Controleer vóór oplevering de afgesproken pagina's op mobiel en desktop en doorloop de contactflow inclusief ongeldige invoer en ontvangst. Geef uitleg over het afgesproken beheer; bij een website zonder CMS wordt duidelijk vastgelegd hoe latere wijzigingen worden aangevraagd.

### Prijsaanpak en terugkerende kosten

Een vaste prijs is logisch wanneer de bovenstaande onderdelen en inhoud bekend zijn. Bij migratie of onduidelijk beheer volgt eerst intake of onderzoek. Domeinregistratie, hosting, zakelijke e-mail, eventuele licenties of maildiensten en onderhoud kunnen terugkeren en worden afzonderlijk zichtbaar gemaakt.

## B. Webapplicatie op maat

### Voor wie en welk probleem

Voor een organisatie die informatie moet navragen, gegevens in losse bestanden bijhoudt of handelingen moeilijk kan volgen. Voorbeelden zijn een begeleide aanvraag, een overzicht met statussen of een beperkte planning van afgesproken activiteiten.

### Wat de klant krijgt en inbegrepen werk — VOORSTEL

- Eerst een procesbeschrijving met begin, einde, gebruikers, gegevens, beslisregels en bekende uitzonderingen.
- Daarna een eerste werkende versie voor **1 afgebakend proces**, in **1 taal**, met maximaal **2 gebruikersrollen**. Een voorbeeld is aanvrager en behandelaar. Een aanvullende beheerrol telt mee en wordt niet als verborgen derde rol verondersteld.
- De voor dat proces afgesproken invoer, opslag of weergave, servercontroles, toegangsrechten en terugkoppeling. Alleen benodigde schermen worden gebouwd.
- Een expliciete lijst met normale en afwijkende gebruikssituaties, plus gerichte controles van de afgesproken werking. De twee voorgestelde feedbackrondes beoordelen eerst de procesopzet en daarna de werkende versie.
- **VOORSTEL:** geen inhoudelijke koppeling met een extern bedrijfsplatform in de basis. E-mailmeldingen, imports, exports en agenda's worden expliciet gekozen; ze zijn niet automatisch inbegrepen.
- Een gebruiksnotitie, uitleg van de afgesproken rollen en handelingen en een overzicht van beheer- en gegevensafspraken.

### Omvang bepalen: meer dan pagina's

Een formulier op één pagina kan meer werk vragen dan vijf eenvoudige informatiepagina's. Leg voor een prijsvoorstel ten minste deze matrix vast:

| Onderwerp         | Concreet vast te leggen                                                                                           |
| ----------------- | ----------------------------------------------------------------------------------------------------------------- |
| Proces            | Startpunt, eindresultaat en welke vervolgstappen buiten de opdracht blijven                                       |
| Rollen en rechten | Wie mag wat bekijken, invoeren, aanpassen en afronden                                                             |
| Gegevens          | Velden, onderlinge relaties, herkomst, verwachte aantallen en keuzes over bewaren/verwijderen                     |
| Regels            | Voorwaarden voor keuzes, berekeningen, aantallen, geldige combinaties en statusovergangen                         |
| Uitzonderingen    | Dubbele invoer, wijziging van eerdere keuzes, ontbrekende capaciteit, gelijktijdig gebruik en mislukte verwerking |
| Koppelingen       | Benodigde systemen, beschikbare documentatie, toegang, testomgeving en gedrag bij uitval                          |

**VOORSTEL:** één proces en twee rollen zijn een startgrens, geen vaste-prijsbelofte voor onbeperkte regels, gegevensvolumes of uitzonderingen. De ingevulde matrix bepaalt de werkelijke bouwomvang.

### Opties en afzonderlijk maatwerk — VOORSTEL

Extra processen of rollen, dashboards met aanvullende rapportages, uitgebreide zoekfilters, bestanden, meldingen, imports/exports, automatische planning, agenda- of boekhoudkoppelingen, gegevensmigratie en grotere aantallen gelijktijdige gebruikers krijgen een eigen omschrijving en begroting. Bij onbekende systemen of moeilijke planningsregels volgt eerst haalbaarheidsonderzoek.

### Buiten de voorgestelde omvang

Een volledige bedrijfsapplicatie, algemeen planningspakket, standaard CRM, native mobiele app, vervanging van alle spreadsheets of een kant-en-klaar onderwijsboekingssysteem wordt niet aangeboden binnen deze basis. Ondersteuning en monitoring na oplevering vallen onder een afzonderlijke afspraak.

### Aanlevering door de klant

Een proceseigenaar, voorbeelden van de gewenste uitkomst, veld- en regelbeschrijvingen, statussen, rollen, uitzonderingen en verwachte gebruiksomvang. Gebruik voor bespreking en testen fictieve voorbeelden. Voor gekozen koppelingen zijn documentatie, bevoegde toegang en een testomgeving nodig. De klant bepaalt met Kevin welke gegevens noodzakelijk zijn en wie ze beheert.

### Feedback, oplevering en overdracht

Beoordeel eerst de procesmatrix. Doorloop de eerste versie daarna per rol met normale en afwijkende voorbeelden. Leg open herstelpunten en uitbreidingswensen apart vast. De overdracht behandelt dagelijks gebruik, rechten, gegevensbeheer en hoe een storing of wijzigingsverzoek wordt gemeld.

### Prijsaanpak en terugkerende kosten

Maatwerk krijgt een prijs na intake. Onbekende koppelingen of regels kunnen aanleiding zijn voor een afzonderlijk begrensde onderzoeksopdracht met als resultaat een voorstel, zonder voorafgaande belofte dat alle wensen bouwbaar zijn. Daarna kan een bouwfase vast worden geprijsd. Hosting, opslag, zakelijke mail, externe API's, monitoring en onderhoud kunnen terugkerende kosten geven; leg ook vast wie deze diensten afneemt.

## C. Webshop of productcatalogus

### Voor wie en welk probleem

Voor een organisatie die haar assortiment begrijpelijk wil presenteren of een passende stap naar aanvragen of bestellen zoekt. Een catalogus helpt producten bekijken en vergelijken. Een webshop voegt een daadwerkelijk bestel- en afhandelproces toe.

### Wat de klant krijgt en inbegrepen cataloguswerk — VOORSTEL

- Een productoverzicht met categorieën en **1 afgesproken producttemplate** voor naam, omschrijving, beelden en kenmerken.
- **VOORSTEL:** maximaal **20 producten** en **5 categorieën** aanvankelijk invoeren, in **1 taal**. Dit begrenst invoerwerk; het is geen technische productlimiet.
- **VOORSTEL:** maximaal **3 ondersteunende informatiepagina's** voor bijvoorbeeld organisatie, contact en gebruik van de catalogus. Extra pagina's worden mee begroot.
- Per product een duidelijke aanvraag- of contactbestemming, bijvoorbeeld een mailto-link met productverwijzing. Een aanvraag is geen bestelling of betaalbevestiging.
- Een afgestemde mobiele en desktopweergave, controle van productinformatie en links, **2 feedbackrondes** en uitleg over de afgesproken manier van bijwerken.

Productgegevens kunnen voor een beperkte catalogus door Kevin worden geplaatst. Zelf producten beheren is een expliciete optie; er wordt geen bestaand productbeheersysteem als bewezen standaardmodule voorgesteld.

### Extra keuzes voor daadwerkelijk online verkopen

Deze onderdelen zijn **niet automatisch inbegrepen**. Voor een webshop worden ze per opdracht gekozen, op haalbaarheid beoordeeld en apart begrensd:

| Onderdeel                      | Eerst bepalen                                                                                                   | Te bouwen of in te richten resultaat als gekozen                                         |
| ------------------------------ | --------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------- |
| Bestellen                      | Alleen aanvragen of bindend bestellen; gast of klantaccount; wanneer een order is afgerond                      | Mandje/checkout, orderregistratie, fout- en bevestigingsschermen                         |
| Betalen                        | Betaalprovider, account van de verkoper, betaalmethoden, munt en afhandeling van mislukte/afgebroken betalingen | Gekozen betaalroute en betrouwbare verwerking van providerberichten                      |
| Voorraad                       | Welke bron leidend is, varianten, reservering en situaties waarin iets uitverkocht raakt                        | Afgesproken voorraadweergave en bijwerking; geen impliciete koppeling met winkelvoorraad |
| Verzending/afhalen             | Gebieden, methoden, tariefregels, vervoerder en wie labels/levering afhandelt                                   | De gekozen leverkeuzes; automatische labels of track-and-trace alleen als apart gekozen  |
| Retouren en terugbetaling      | Wie verzoeken beoordeelt, welke stappen nodig zijn en hoe een betaling wordt teruggedraaid                      | De afgesproken verwerking; geen automatisch retourportaal                                |
| Boekhouding en andere systemen | Doelsysteem, beschikbare API/export, veldkoppeling, testtoegang en verwerking van fouten                        | Een benoemde koppeling of export; geen onbeperkte synchronisatie                         |

### Opties en afzonderlijk maatwerk — VOORSTEL

Zelfbeheer, zoekfilters, productvarianten, grotere invoer- of importopdrachten, meerdere talen, klantaccounts en de gekozen verkooponderdelen hierboven worden apart begroot. Eén extra productvariant kan ook invloed hebben op prijs, voorraad en orderverwerking; alleen aantallen producten tellen is dus onvoldoende.

### Buiten de voorgestelde omvang

De catalogusbasis bevat geen mandje, checkout, betaalverwerking, voorraadkoppeling, verzendintegratie, retourportaal of boekhoudkoppeling. Ook een marktplaats met meerdere verkopers, abonnementenverkoop, fotografie, productteksten vanaf nul en doorlopende orderafhandeling vallen erbuiten. Er is in de gelezen projectonderbouwing geen afgeronde webshop aangetoond.

### Aanlevering door de klant

Een geordend productbestand met namen, kenmerken, categorieën, toegestane beelden en vastgestelde productinformatie; duidelijke keuzes over getoonde prijzen en verkoopwijze. Voor online verkoop zijn daarnaast de operationele afspraken, benodigde accounttoegang, providerkeuze en door de klant vastgestelde verkoopinformatie nodig. Kevin bepaalt hier geen fiscale of juridische voorwaarden.

### Feedback, oplevering en overdracht

Beoordeel eerst een representatief product en de categorie-indeling, daarna de ingerichte catalogus. Bij online verkoop hoort een aparte controle van normale en mislukte bestellingen, betaaluitkomsten en de gekozen voorraad-/leverregels. Productieactivering volgt pas na een afgesproken oplevercontrole. Leg uit hoe producten en eventuele orders worden beheerd.

### Prijsaanpak en terugkerende kosten

Een catalogus kan vast worden geprijsd bij bekende inhoud en afbakening. Een webshop krijgt eerst een intake en een aparte begroting van verkooponderdelen. Mogelijke kosten zijn hosting, platform- of pluginlicenties, betaaltransacties, mail, opslag en externe koppelingen. Kosten van levering en retourafhandeling blijven operationele keuzes van de verkoper. Er worden geen providerprijzen of tarieven ingevuld.

## D. Onderhoud en doorontwikkeling

### Voor wie en welk probleem

Voor een eigenaar die wil weten wie de techniek bijhoudt, wat er bij een storing gebeurt en hoe nieuwe wensen worden opgepakt. Ook een bestaande website kan hiervoor worden besproken; eerst moet duidelijk zijn wat Kevin kan beheren.

### Wat de klant krijgt — vier afzonderlijk af te spreken onderdelen

| Onderdeel                                   | Inbegrepen werk als dit wordt gekozen — VOORSTEL                                                                                                               | Grens en afzonderlijke opties                                                                                                                                              |
| ------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Updates en technisch beheer                 | Inventarisatie van de afgesproken toepassing en afhankelijkheden; updates voorbereiden, toepassen en de belangrijkste functies nalopen; wijzigingen vastleggen | Grote versieovergangen, verouderde maatwerkcode en herstel van onbekende bestaande gebreken eerst beoordelen en begroten                                                   |
| Back-ups en herstel                         | Vastleggen welke code, bestanden en gegevens worden meegenomen, waar kopieën staan, wie controleert en hoe herstel wordt beproefd                              | Frequentie, bewaartermijn, aparte opslag, maximaal aanvaardbaar gegevensverlies en hersteldoel nog bepalen; een VPS-snapshot is niet vanzelf een gecontroleerd herstelplan |
| Ondersteuning                               | Een afgesproken kanaal voor vragen en storingen, beoordeling en terugkoppeling over de vervolgstap                                                             | Vensters, prioriteiten, beschikbare inzet en reactieafspraken nog bepalen; geen onbeperkte hulp of permanente bereikbaarheid                                               |
| Nieuwe functies en inhoudelijke wijzigingen | Wensen verzamelen, omvang bespreken en afzonderlijk laten goedkeuren                                                                                           | Nieuwe pagina's, tekst-/productwijzigingen, extra regels en koppelingen zijn geen automatische onderhoudsuren                                                              |

### Voorgestelde basis en opties

**VOORSTEL:** begin met onderhoud voor **1 benoemde website of applicatie** en een expliciete lijst van bijbehorende diensten. Neem updates en technisch beheer alleen op na een eerste technische inventarisatie. Beslis over back-ups/herstel en ondersteuning als afzonderlijke regels in dezelfde afspraak; doorontwikkeling blijft een afzonderlijke opdracht.

**VOORSTEL:** spreek één evaluatiemoment per afgesproken beheerperiode af. De duur van die periode, updatefrequentie, hoeveelheid ondersteuning en kosten zijn nog te bepalen. Extra systemen, monitoring, afzonderlijke back-upopslag, grotere updates, werkzaamheden buiten afgesproken tijden en hulp bij overname zijn opties.

### Buiten de voorgestelde omvang

Onbeperkte wijzigingen, herstel van iedere externe storing, permanente monitoring door een persoon, vervanging tijdens afwezigheid en beschikbaarheidsgaranties zijn niet impliciet inbegrepen. **Hosting op Kevins VPS betekent niet dat onbeperkt onderhoud of permanente bereikbaarheid inbegrepen is.** Hosting, beheer en ondersteuning krijgen elk een eigen omschrijving.

### Aanlevering door de klant

Een overzicht van systemen en accounts, bekende problemen, verantwoordelijken, bestaande licenties, eventuele beheer- en back-upafspraken en passende toegang. Bespreek welke functies voor het dagelijkse werk essentieel zijn en wie bij incidenten beslissingen neemt. Neem geen wachtwoorden op in het voorstel of de toekomstige offerteaanvraag.

### Feedback, oplevering en overdracht

Begin met de technische inventarisatie en een overzicht van wat wel en niet wordt beheerd. Rapporteer uitgevoerde werkzaamheden en open aandachtspunten op het afgesproken moment. Een nieuw kenmerk of grotere wijziging krijgt eigen feedback- en opleverafspraken. Bespreek bij overdracht hoe toegang, code, gegevens, back-ups en open taken worden overgedragen; de procedure en inspanning worden vooraf begrensd.

### Prijsaanpak en terugkerende kosten

Een afzonderlijke terugkerende afspraak is mogelijk voor voorspelbaar beheer. Losse ondersteuning of doorontwikkeling kan per opdracht worden begroot. De gekozen systemen, hoeveelheid werk, ondersteuning en herstelafspraken bepalen de prijs; er is nog geen tarief of urenbundel vastgesteld. Domeinen, VPS/hosting, externe back-ups, monitoring, licenties en maildiensten kunnen los hiervan kosten geven.

## Conceptteksten voor toekomstige dienstenkaarten

**Concept, pas toepassen na beoordeling.** Deze kaarten bevatten bewust geen onbevestigde prijzen, harde pakketgrenzen of reactietijden.

| Kaart                         | Concepttekst                                                                                                                                                                   | Bestaande bestemming — VOORSTEL                                                                             |
| ----------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ----------------------------------------------------------------------------------------------------------- |
| Website                       | Je bezoekers moeten snel begrijpen wat je doet en hoe ze je bereiken. Ik breng je aanbod, werk en contact samen op een heldere website die prettig werkt op mobiel en desktop. | “Bekijk websites” → `/diensten/websites`                                                                    |
| Webapplicatie op maat         | Komen aanvragen onvolledig binnen of mis je overzicht in je werk? Ik help je één proces afbakenen en bouw de afgesproken formulieren, controles en overzichten.                | “Bekijk webapplicaties” → bestaande route `/diensten/dashboards`, na inhoudelijke verbreding van die pagina |
| Webshop of productcatalogus   | Wil je producten laten zien, aanvragen ontvangen of online verkopen? Ik help je kiezen wat nodig is, van een heldere catalogus tot een afgesproken bestelproces.               | “Bekijk webshops en catalogi” → `/diensten/webshops`                                                        |
| Onderhoud en doorontwikkeling | Wil je de techniek laten bijhouden of je website verder uitbreiden? Ik spreek met je af wat ik beheer, welke ondersteuning past en hoe we nieuwe wensen aanpakken.             | “Bespreek onderhoud” → `/contact`                                                                           |

## Voorstel voor toepassing in de huidige pagina's

| Bestand of pagina                            | Latere toepassing — VOORSTEL                                                                                                                                                       |
| -------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `resources/js/pages/Services/Index.vue`      | Behoud het bestaande grid met vier kaarten. Maak daarvan de drie hoofdaanbiedingen plus Onderhoud en doorontwikkeling. Verwerk formulieren herkenbaar binnen Webapplicatie op maat |
| `resources/js/pages/Services/Websites.vue`   | Voeg na akkoord compacte blokken toe over geschikt gebruik, bevestigde basisomvang, opties, aanlevering en werkwijze                                                               |
| `resources/js/pages/Services/Dashboards.vue` | Verbreed de inhoud naar Webapplicatie op maat, met formulieren en beheer als voorbeelden. Behoud de bestaande URL; introduceer geen nieuwe route alleen voor de naam               |
| `resources/js/pages/Services/Webshops.vue`   | Zet productpresentatie en daadwerkelijk verkopen naast elkaar. Benoem uitsluitend bevestigde verkooponderdelen als inbegrepen                                                      |
| Home, Werk en de onderwijsformulier-case     | Houd de persoonlijke introductie en case intact. Stem later alleen dienstnamen en verwijzingen af; geen demoactie zolang er geen werkende demo bestaat                             |
| Contact                                      | Behoud nu de gewone mailto-link. Een toekomstige offerte-intake kan naar aanbod en gewenste opties vragen wanneer de echte verwerking gebouwd is                                   |
| `config/projects.php`                        | Blijft de centrale bron voor projectinformatie; voeg geen pakketvoorwaarden of onbewezen cases toe                                                                                 |

De latere toepassing gebruikt bestaande kleuren, typografie, knoppen, focusstijlen en routes. Er is in deze stap geen publieke wijziging gedaan en geen nieuwe configuratiestructuur aangelegd.

## Koppeling met de toekomstige offertefunctie — VOORSTEL

Gebruik het aanbod als begin van een gesprek, niet als automatische toezegging. Laat een toekomstige intake het gekozen aanbod, het doel, doelgroep, bestaande situatie, inhoud, gewenste opties en voorkeursplanning verzamelen. Geef onbesliste keuzes de status “te bespreken”.

Een offerte kan daarna per onderdeel vastleggen: inbegrepen werk, gekozen opties, afhankelijkheden, aanlevering, feedback, acceptatie, terugkerende kosten en uitgesloten werk. Bij een webapplicatie hoort de procesmatrix erbij; bij een webshop de gekozen verkooponderdelen. Onbekende koppelingen krijgen eerst een onderzoekspunt, geen automatisch bedrag.

Opties mogen elkaar beïnvloeden: een tweede taal vraagt ook vertaling en controle; een voorraadkoppeling beïnvloedt bestelgedrag; een beheeromgeving vraagt rechten en overdracht. Een toekomstig formulier mag deze keuzes verzamelen, maar mag de haalbaarheid of prijs niet zelfstandig bevestigen. Er zijn nu geen database, beheeromgeving, pakket-ID's of ongebruikte configuratieobjecten gebouwd.

## Nodig om prijzen en termijnen vast te leggen

Kevin moet vóór vaststelling ten minste bepalen:

- Een interne rekenbasis voor bouw, overleg, inhoudsverwerking, testen, overdracht en onzekerheid; dit document kiest geen tarief.
- Beschikbare capaciteit, gelijktijdige opdrachten en hoe planning afhangt van volledige aanlevering, feedback en externe partijen.
- Welke startgrenzen hij daadwerkelijk wil aanbieden en wanneer een intake of afzonderlijk onderzoek nodig is.
- Welke hosting-, mail-, licentie- en andere externe kosten door wie worden afgenomen en hoe terugkerend werk wordt begroot.
- Welke herstel-, ondersteunings- en overdrachtsinzet hij verantwoord kan afspreken.

Een gewenste datum van de klant wordt pas een leverafspraak nadat omvang, capaciteit en afhankelijkheden zijn beoordeeld.

## Eigendom, toegang en overdracht: bespreekpunten

Dit zijn onderwerpen voor latere afspraken, geen juridische voorwaarden:

| Onderdeel            | Te bespreken                                                                                                                                                             |
| -------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Domein               | Op wiens naam registratie plaatsvindt, wie verlengt en hoe toegang of verhuizing wordt geregeld                                                                          |
| Accounts             | Wie eigenaar en betaler is van hosting, mail, betaalprovider en andere diensten; welke gedelegeerde toegang Kevin nodig heeft                                            |
| Broncode             | Welke eigen code, repositorytoegang, documentatie en bouwinstructies worden geleverd; rechten op bestaand werk en voorwaarden van gebruikte onderdelen eerst vaststellen |
| Content en beeld     | Herkomst en gebruiksrechten, wie inhoud goedkeurt en hoe originele bestanden worden overgedragen                                                                         |
| Gegevens en back-ups | Wie toegang heeft, benodigde exportformaten, opslag en bewaren/verwijderen, en wat een overdracht of herstel omvat                                                       |

Leg een eventuele verhuizing naar een andere beheerder, het intrekken van toegang en de benodigde overdrachtswerkzaamheden concreet vast. Veronderstel niet dat toegang tot een account hetzelfde is als eigendom van alle bijbehorende code, content of gegevens.

## Vijf gebundelde beslisvragen voor Kevin

1. **Aanbod en basisomvang:** wil je deze drie hoofdaanbiedingen plus onderhoud voeren, en welke VOORSTEL-grenzen bevestig of wijzig je: 5 websitepagina's, 1 applicatieproces/2 rollen, 20 catalogusproducten/5 categorieën/3 informatiepagina's, 1 taal en 2 feedbackrondes?
2. **Inbegrepen functies en beheer:** wil je de eenvoudige contactflow standaard bij Website, en zelfbeheer, imports, koppelingen en verkooponderdelen alleen als expliciete opties; welke functies wil je eerst verder onderzoeken?
3. **Prijs en planning:** welke interne rekenbasis, beschikbare capaciteit en onzekerheidsruimte wil je gebruiken, en wanneer kies je een vaste prijs, afzonderlijk onderzoek of een prijs na intake?
4. **Onderhoud en hosting:** wat wil je per systeem bieden aan updates, back-ups/herstel en ondersteuning, met welke uitvoeringsmomenten en begrensde inzet, en welke diensten lopen via jouw VPS of rechtstreeks via de klant?
5. **Eigendom en overdracht:** hoe wil je domeinen, accounts, broncode, content en gegevens laten beheren en overdragen, en welke documentatie en uitleg moeten onderdeel van de opdracht zijn?

## Controle en vervolgstap

Het voorstel is gecontroleerd op interne status, expliciete VOORSTEL-grenzen, onderscheid tussen aangetoond/nieuw/onderzoek, bestaande linkbestemmingen en maximaal vijf gebundelde beslisvragen. Er zijn geen bedragen, definitieve termijnen of garanties toegevoegd.

De afzonderlijke resterende beeldcontrole van Werk, de onderwijsformulier-case en Diensten staat in [beeldcontrole stap 5](beeldcontrole-stap-5.md), met twaalf screenshots en een onderscheid tussen browsermetingen en daadwerkelijke beeldbeoordeling.

Na Kevins inhoudelijke beoordeling kunnen bevestigde onderdelen in een volgende stap in de bestaande pagina's worden toegepast. Het echte contactformulier met zakelijke e-mail en de interactieve onderwijsformulier-demo blijven verplicht vóór de eerste publieke release; dit document bouwt of vervangt die functies niet.
