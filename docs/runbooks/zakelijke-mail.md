# Zakelijke mail — run 7–8

Lokale oplevering op 21 september 2026, vanaf `ac27aff73c9c231d5ea2c5fa677b9e8833db8f1c` op `feature/public-visual-refinement`. Laravel 13.22.0, Symfony Mailer 8.1.1 en PHP 8.4.15; bestaande dependencies.

## 1. Voorbeelden bekijken

Voer uit in PowerShell:

```powershell
Set-Location -LiteralPath 'C:\wamp64\www\kevin-webstudio'
$env:Path = 'C:\wamp64\bin\php\php8.4.15;' + $env:Path
php artisan studio:mail-preview
Start-Process '.\storage\app\private\mail-preview\index.html'
```

De index opent beide mails, de lange varianten en de handtekening als HTML of tekst. Rechtstreeks openen:

```powershell
Start-Process '.\storage\app\private\mail-preview\aanvraag.html'
Start-Process '.\storage\app\private\mail-preview\ontvangst.html'
Start-Process '.\storage\app\private\mail-preview\handtekening.html'
```

Alles staat in `C:\wamp64\www\kevin-webstudio\storage\app\private\mail-preview`, genegeerd door Git en zonder publieke previewroute. Laravel/Vite hoeven hiervoor niet te draaien.

Het commando rendert de echte mailclasses en templates, zonder `send()` of aanvraagopslag. De mailvoorbeelden gebruiken fictieve contactgegevens onder `example.com`. Alleen de aparte handtekening bevat Kevins echte zakelijke gegevens. Ontbrekende SMTP-instellingen blokkeren de preview niet.

## 2. SMTP zelf instellen

De [officiële mailboxinstellingen van TransIP](https://www.transip.nl/knowledgebase/309-algemene-instellingen-voor-mijn-e-mailadres), gecontroleerd op 20 september 2026, noemen `smtp.transip.email`, poort 465, SSL en authenticatie met het volledige mailboxadres en mailboxwachtwoord. Dit profiel past bij de bevestigde TransIP-mailbox; de website blijft op de eigen VPS.

Pas zelf je lokale `.env` aan:

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=smtps
MAIL_URL=null
MAIL_HOST=smtp.transip.email
MAIL_PORT=465
MAIL_USERNAME=info@kevinwebstudio.nl
MAIL_PASSWORD=""
MAIL_FROM_ADDRESS="info@kevinwebstudio.nl"
MAIL_FROM_NAME="Kevin Webstudio"
STUDIO_MAIL_TO_ADDRESS="info@kevinwebstudio.nl"
STUDIO_MAIL_RECEIPT_FROM_NAME="Kevin | Kevin Webstudio"
```

Vul `MAIL_PASSWORD` zelf met het mailboxwachtwoord, uitsluitend in `.env`. `smtps` gebruikt impliciete TLS op poort 465; certificaatverificatie blijft aan. Gebruik geen `MAIL_ENCRYPTION`: deze applicatie leest `MAIL_SCHEME`. Het beperkte testcommando weigert een ingevulde `MAIL_URL`, zodat die geen losse instellingen kan overschrijven. Gewone Laravel-mail behoudt URL-ondersteuning.

`.env.example` blijft veilig op `MAIL_MAILER=log`, zonder geheimen. Na aanpassen:

```powershell
php artisan config:clear
php artisan studio:mail-test --to=kevin.webappbeheer@outlook.com
```

Dit controleert instellingen en maakt previews, zonder SMTP te benaderen. Meldingen tonen geen geheime waarden. Tijdens deze run voldeed de lokale configuratie nog niet aan het testprofiel: mailer, server/beveiliging, mailboxgegevens en afzender moeten worden nagelopen.

De bevestigde MX/SPF/DKIM/DMARC-records blijven behouden. Zie [mailbox bij TransIP, website elders](https://www.transip.nl/knowledgebase/email-algemeen/294-e-mail-transip-gebruiken-terwijl-website). Extra webhosting-authenticatierecords uit [websiteverzending bij TransIP](https://www.transip.nl/knowledgebase/7186-mijn-website-verstuurt-geen-e-mail) worden niet op deze VPS-app toegepast.

## 3. Later bewust één testmail versturen

Na beoordeling en SMTP-instelling voert Kevin zelf uit:

```powershell
php artisan studio:mail-test --to=kevin.webappbeheer@outlook.com --send
```

Je kunt de ontvanger vervangen door `info@kevinwebstudio.nl`. Andere adressen worden geweigerd. Iedere uitvoering verstuurt hoogstens één aanvraagmelding met fictieve inhoud en onderwerp `[TEST] Nieuwe contactaanvraag — Kevin Webstudio`. De bevestiging wordt hiermee niet verstuurd.

Het commando vereist echt SMTP, zonder terugval naar log. Ontbrekende instellingen of transportfouten geven exitcode 1; `$LASTEXITCODE` toont die code. Gevoelige serverantwoorden worden niet afgedrukt of gelogd. Controleer bij een fout je mailbox vóór opnieuw proberen.

Succes betekent uitsluitend **acceptatie door SMTP**, geen bewezen aflevering. De beperking tot eigen testontvangers zit alleen in dit CLI-commando, niet in de herbruikbare mailclasses.

## 4. Ontvangst en headers controleren

1. Zoek het testonderwerp in de gekozen mailbox, ook in spam. Controleer inhoud, logo, mobiel en de weergave met afbeeldingen geblokkeerd.
2. Controleer `From: Kevin Webstudio <info@kevinwebstudio.nl>` en de gekozen `To`. Klik op Beantwoorden: `robin.voorbeeld@example.com` moet verschijnen. Controleer dit zonder een antwoord naar het fictieve adres te versturen.
3. Open de volledige headers/berichtbron. Nieuw Outlook en Outlook.com: **Meer acties → Weergave → Berichtdetails weergeven**. Klassiek Outlook: open het bericht afzonderlijk, dan **Bestand → Eigenschappen → Internetheaders**. Zie [Microsofts instructies](https://support.microsoft.com/nl-nl/outlook/view-internet-message-headers-in-outlook). Gebruik in TransIP-webmail de volledige berichtbron.
4. Lees `Authentication-Results` van de ontvangende server: zoek `spf=pass`, `dkim=pass` en `dmarc=pass`. Bekijk `smtp.mailfrom`, DKIM `header.d` en DMARC `header.from=kevinwebstudio.nl`. Voor DMARC moet een geslaagde SPF- of DKIM-controle ook aansluiten bij het zichtbare afzenderdomein. Een aanwezige DKIM-handtekening alleen bewijst niets. Zie [DMARC, RFC 9989](https://www.rfc-editor.org/rfc/rfc9989.html).
5. Noteer SMTP-acceptatie, daadwerkelijke ontvangst, map, mailclient, antwoordadres en authenticatieresultaten afzonderlijk. `p=none` is een monitorbeleid, geen geslaagde authenticatie. Ontbrekende resultaten blijven een open controle.

De bezoekerbevestiging hoort later `Kevin | Kevin Webstudio <info@kevinwebstudio.nl>` en `Reply-To: info@kevinwebstudio.nl` te hebben. Die headers zijn automatisch getest; deze mail is niet echt verstuurd.

## 5. Handtekening en run 9

Kopieer de zichtbare inhoud van `handtekening.html` naar de handtekeningeditor, of gebruik `handtekening.txt`. Bij invoegen als HTML-bron gebruik je het HTML-bestand zelf. De basis bevat geen afbeeldingen en werkt zelfstandig.

Stel dit apart in ieder gebruikt programma in. TransIP-webmail: **Instellingen → Identiteiten → eigen adres → Ondertekening**, met HTML-ondertekening voor opmaak; zie de [TransIP-handleiding](https://www.transip.nl/knowledgebase/380-wil-handtekening-instellen-transip-webmail). Maak in Outlook apart een handtekening bij je account. Er is niets in een mailbox of mailprogramma geïnstalleerd.

Voeg later eventueel via de afbeeldingsknop van de mailclient `public/brand/kevin-webstudio-kds-mark.png` toe, op circa 68 px afbeeldingsbreedte. Het originele PNG is circa 286 KB; TransIP-webmail staat maximaal 64 KB handtekeningafbeeldingen toe. Maak daarvoor eerst een kleinere kopie van hetzelfde PNG, zonder het bronbestand te vervangen. Controleer daarna een handmatig antwoord. Een los HTML-bestand neemt geen Laravel-CID-bijlage over.

`ContactEnquiryNotification` en `ContactEnquiryReceipt` delen de opmaak. `ContactEnquiry` bevat naam, gevalideerd adres, dienst en bericht. De melding gebruikt de aanvrager als Reply-To; de bevestiging bewaart alleen adres en een beperkte begroeting. HTML-views ontsnappen invoer; tekstviews bewaren tekst letterlijk voor `text/plain`. Er is geen eigen viewvariabele `message`.

Run 9 voegt formulier, validatie/lengtegrenzen, spambeperking, betrouwbare opslag, verzending **na succesvolle opslag/commit**, retries, bescherming tegen dubbele verzending en bezoekersfeedback toe. Contactformulier én interactieve onderwijsdemo blijven verplicht voor release 1.

## Uitgevoerde controle

| Controle | Resultaat |
| --- | --- |
| Pint `--test` | Geslaagd |
| PHPStan | Geslaagd, 0 fouten |
| Composer validate | Geslaagd |
| Git diff --check en bestandscontrole | Geslaagd; bestaande runbookwijziging behouden |
| PHP-testsuite | 144 geslaagd, 763 assertions; inclusief 41 gerichte mailtests |
| Testdatabase en transport | Expliciet SQLite `:memory:` en array/fake; geen echte verzending |
| Preview en standaard testcommando | Geslaagd; SMTP niet benaderd |
| Browser | Normale en lange inhoud op 390 en 1440 px, zonder horizontale overloop; leesbaarheid, logo, alinea's en mailto-doelen beoordeeld |
| Donkere modus / afbeelding verborgen | In Chrome nagebootst; tekstueel woordmerk blijft leesbaar |
| SMTP-acceptatie / ontvangst / SPF-DKIM-DMARC | **Niet getest** |
| Echte Outlook-/Gmail-rendering | **Niet getest** |

Het originele PNG is 1254 × 1254 px, met circa 849 × 833 px zichtbaar beeld; de HTML gebruikt 68 × 68 px. Verzonden MIME bevat het originele PNG via CID. Alleen de lokale renderer zet CID om naar een databron. Alle normale en lange screenshots zijn daadwerkelijk in de beeldweergave bekeken. De standaardviewer had een sandboxfout; de alternatieve inline beeldweergave werkte, zonder base64 als tekst te publiceren.

| Voorbeeld | 390 px | 1440 px |
| --- | --- | --- |
| Aanvraagmelding | [Mobiel](screenshots/mail/aanvraag-390.png) | [Desktop](screenshots/mail/aanvraag-1440.png) |
| Ontvangstbevestiging | [Mobiel](screenshots/mail/ontvangst-390.png) | [Desktop](screenshots/mail/ontvangst-1440.png) |

Basis: [Laravel 13 Mail](https://laravel.com/framework/docs/13.x/mail) en de geïnstalleerde frameworkcode. Frontendcontroles en build waren niet nodig: Vue, Vite, frontendassets en routes zijn niet gewijzigd. De publieke website en bestaande wijziging aan `lokaal-ontwikkelen-en-git.md` zijn behouden. Geen dependencies toegevoegd, echte aanvragen aangemaakt, merge of deployment uitgevoerd.
