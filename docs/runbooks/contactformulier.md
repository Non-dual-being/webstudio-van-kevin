# Contactformulier — run 9

Gebouwd op 26 september 2026 en lokaal afgerond op **3 oktober 2026** in `C:\wamp64\www\kevin-webstudio`, branch `feature/public-visual-refinement`, basis `ac27aff73c9c231d5ea2c5fa677b9e8833db8f1c`. PHP **8.4.15**. De bestaande mail- en contactimplementatie is behouden; de afronding wijzigt alleen veldfocus, textarea, succeskaart en de bijbehorende documentatie en screenshots. Geen dependencies toegevoegd, merge of deployment uitgevoerd.

## Bekijken en lokaal starten

**Actieve geïsoleerde preview, uitsluitend testgegevens: http://127.0.0.1:8109/contact**

**Gewone lokale pagina, na starten voor Kevins bewuste echte eindtest: http://127.0.0.1:8098/contact**

Op 3 oktober is alleen de nieuwe geïsoleerde preview op 8109 gestart en gecontroleerd. Deze gebruikt een eigen SQLite-database, sessiecookie, cache en queueverbinding met expliciet arraytransport. De gewone server gebruikt de bestaande ontwikkeldatabase en `.env`; die server start je indien nodig met het commando hieronder. Er is tijdens de afronding geen echte contactmail-worker gestart. Een formulierinzending slaat op en plant twee taken in, maar verstuurt zelf geen mail.

De actuele browsercontrole gebruikte uitsluitend poort 8109 en `C:\Users\kevin\AppData\Local\Temp\kevin-contact-finish-20261003\preview.sqlite`. Twee fictieve aanvragen hebben elk twee `intercepted`-statussen, met één poging per mail; de testqueue is leeg. Helpers, browserprofiel, controlelogs en `browser-results.json` staan buiten de repository in dezelfde tijdelijke map. Alleen de screenshots bevatten vastgelegde testinhoud. Deze database en taken worden nooit aan de gewone worker aangeboden. Er blijft geen testworker actief.

De werkende SMTP-instellingen, goedgekeurde mailtemplates en het oorspronkelijke logo zijn niet gewijzigd. Controlehashes van `.env`, de mailconfiguratie, mails, publieke layout en `lokaal-ontwikkelen-en-git.md` zijn vóór en na de controles vergeleken. Ook de inhoud van bestaande contactaanvragen, mailstatussen en queuetabellen is alleen-lezen vergeleken en behouden. De eerdere preview op 8099 en tijdelijke map `kevin-contact-run9-20260926` horen bij de eerste controle van 26 september; hun taken zijn niet verwerkt bij deze afronding.

Als de gewone lokale server niet meer draait, open PowerShell:

```powershell
Set-Location -LiteralPath 'C:\wamp64\www\kevin-webstudio'
$env:Path = 'C:\wamp64\bin\php\php8.4.15;' + $env:Path
php -v
php artisan serve --host=127.0.0.1 --port=8098 --tries=1
```

De gebouwde assets staan klaar; Vite hoeft niet te draaien om te bekijken. Voor verdere frontendontwikkeling, in een tweede PowerShell-venster:

```powershell
Set-Location -LiteralPath 'C:\wamp64\www\kevin-webstudio'
$env:Path = 'C:\wamp64\bin\php\php8.4.15;' + $env:Path
pnpm.cmd dev --host=127.0.0.1 --port=5173 --strictPort
```

De geïsoleerde achtergrondserver draait rechtstreeks op PHP 8.4.15 met Laravels eigen ontwikkelrouter. Het bovenstaande Artisan-commando is bedoeld voor een open, interactief terminalvenster. Start geen tweede server op een al gebruikte poort. Gebruik voor automatische hercontroles altijd een aparte database en testtransport, inclusief aparte databaseverbindingen voor queue, cache en sessies; de gewone URL is daarvoor niet bedoeld.

## Wat is gebouwd

- `GET /contact` behoudt `contact.create`; `POST /contact` heet `contact.store`. De pagina gebruikt Inertia 3 `useForm` en gegenereerde Wayfinder-routes.
- Naam (2–120 tekens), e-mailadres (maximaal 254), optionele dienst en bericht (20–5000 tekens). `App\Enums\ContactService` is de gedeelde bron voor formulierkeuzes, validatie en dienstlabels in de mail. De labels sluiten aan op de bestaande dienstenpagina.
- Nederlandse foutmeldingen, echte labels, gekoppelde foutteksten, focus bij fouten en succes, behoud van invoer bij fouten/verlopen sessie, een wachtstand en bescherming tegen dubbel klikken. Na opslag wordt het formulier gewist. De tekst bevestigt ontvangst, geen mailbezorging.
- Afronding 3 oktober: contactvelden hebben een blauwe focusrand met een subtiele buitenring; echte fouten behouden rode randen en gekoppelde fouttekst. De textarea is `12rem` hoog, responsief breed, niet handmatig verstelbaar en verticaal scrollbaar. De succeskaart heeft een lichte neutrale achtergrond, subtiele rand, donkerblauwe tekst en een klein decoratief vinkje; `role="status"` en de verplaatste focus blijven behouden. De tekst blijft: “Je bericht is ontvangen. Ik neem contact met je op.”
- Honeypot buiten toetsenbord- en schermlezerinteractie, standaard CSRF/sessiebescherming en gedeelde limieten. Geen captcha, verplichte toestemming of extra commerciële velden.
- `ContactSubmission` bewaart uitsluitend de aanvraag, tijdstippen, een interne ULID-referentie en hashes voor idempotentie. Geen publieke leesroute of nieuw beheerpaneel.
- Elke formulierpoging heeft een willekeurige, met de sessie ondertekende sleutel. Uniciteit in de database voorkomt dubbele opslag. Dezelfde sleutel met andere inhoud of een andere sessie wordt geweigerd. Na succes volgt een nieuwe sleutel.
- `ContactMailDelivery` bewaart per aanvraag twee unieke verzendintenties: `notification` en `receipt`. Aanvraag en intenties worden in één transactie opgeslagen. Enqueue gebeurt expliciet na commit, hoewel de algemene queueconfiguratie `after_commit=false` behoudt.

**Hergebruikt uit run 7–8, ongewijzigd:** `App\Data\ContactEnquiry`, `ContactEnquiryNotification`, `ContactEnquiryReceipt`, hun HTML-/teksttemplates en CID-logo, `config/mail.php` en `config/studio-mail.php`. De melding gebruikt de aanvrager als Reply-To. De beperkte ontvangstbevestiging bevat geen ingezonden berichttekst, dienst, links of bijlagen van de bezoeker; Reply-To blijft het zakelijke afzenderadres. De oorspronkelijke header, het KDS-logo en de bestaande publieke knopstijl blijven behouden.

**Nieuw in run 9:** controller/FormRequest, dienstenenum, opslagactie, twee modellen, één migratie, sessiesleutelhelper, maildispatcher/sender/job, CLI, contactconfiguratie en drie gerichte testbestanden. Daarnaast zijn Contact, Privacy, web-/consoleroutes en de uitsluitend op contact gerichte 419-afhandeling aangepast. De eerdere wijziging aan `lokaal-ontwikkelen-en-git.md` is behouden.

## Lokale migratie en gegevens

Toegepast: `2026_09_26_000000_create_contact_submissions_and_deliveries.php`. Deze voegt `contact_submissions` en `contact_mail_deliveries` toe. Alle bestaande migraties waren vooraf uitgevoerd. Alleen de nieuwe migratie is op de ontwikkeldatabase toegepast; geen fresh/reset/rollback.

Vooraf is met SQLite `VACUUM INTO` een consistente backup gemaakt buiten Git:

`C:\Users\kevin\AppData\Local\Temp\kevin-contact-run9-20260926\development-before-run9.sqlite`

Bestaande aantallen gebruikers, teams en queuetaken zijn bij de oorspronkelijke migratie behouden. Ook op 3 oktober is geen automatische testaanvraag aan de gewone ontwikkeldatabase toegevoegd en is daarop geen migratie uitgevoerd. PHPUnit gebruikt SQLite `:memory:`; de gelijktijdigheidstest gebruikt een eigen tijdelijk SQLite-bestand met twee echte PHP-processen. De browserpreview heeft opnieuw een afzonderlijk tijdelijk bestand.

## Status bekijken en gericht herstellen

Gebruik een nieuw PowerShell-venster met de gewone projectomgeving, zonder previewvariabelen:

```powershell
Set-Location -LiteralPath 'C:\wamp64\www\kevin-webstudio'
$env:Path = 'C:\wamp64\bin\php\php8.4.15;' + $env:Path
php artisan contact:mail
```

Dit is alleen-lezen en toont de laatste 20 aanvragen met referentie, ontvangsttijd, beide mailstatussen, pogingen en foutcategorie. Namen, adressen en berichten verschijnen hier niet. Neem een referentie over voor details:

```powershell
$contactReference = 'NEEM_HIER_DE_REFERENTIE_OVER'
php artisan contact:mail $contactReference
php artisan contact:mail $contactReference --show
```

`--show` toont bewust de inhoud van die ene aanvraag, uitsluitend via CLI; behandel die terminaluitvoer als persoonsgegevens. De applicatielogs bevatten alleen interne verwijzingen en beperkte foutcategorieën, geen berichttekst, wachtwoorden of onbewerkte SMTP-antwoorden.

| Status | Betekenis / actie |
| --- | --- |
| `pending` | Duurzaam opgeslagen; wacht op enqueue of volgende retry. |
| `queued` | Een taak staat klaar op `contact-mail`. |
| `processing` | Eén worker heeft de intentie atomair geclaimd. |
| `accepted` | SMTP heeft de mail geaccepteerd; daadwerkelijke bezorging nog in mailbox controleren. |
| `intercepted` | Alleen expliciet lokaal/test-arraytransport; niet verstuurd. |
| `failed` | Definitieve afwijzing, ongeschikt transport/configuratie of retrygrens bereikt. |
| `uncertain` | Acceptatie is onzeker, bijvoorbeeld door verbroken verbinding of procescrash. Eerst mailbox/provider controleren. |

Wachtende of na een enqueuefout vergeten intenties van één aanvraag opnieuw aanbieden:

```powershell
php artisan contact:mail $contactReference --dispatch
```

Een mislukte bevestiging na het verhelpen van de oorzaak expliciet herstellen:

```powershell
php artisan contact:mail $contactReference --retry=receipt
```

Gebruik `--retry=notification` uitsluitend voor de interne melding. Dit raakt de geaccepteerde andere mail niet. Een geaccepteerde of onderschepte mail kan hiermee niet worden herhaald. Het totale aantal pogingen blijft bewaard; een expliciet herstel geeft maximaal drie nieuwe pogingen.

Voor een onzekere mail is extra bevestiging nodig, **nadat je hebt nagegaan of deze al is aangekomen/geaccepteerd**:

```powershell
php artisan contact:mail $contactReference --retry=receipt --confirm-uncertain
```

Dit is een bewuste herverzendactie, geen onschuldige statuscontrole. Gewone SMTP biedt geen gegarandeerde exactly-once-bezorging. Een proces kan direct na acceptatie crashen, vóór het opslaan van de status. Een oude `processing`-claim wordt daarom `uncertain`, nooit automatisch opnieuw verstuurd. Ook een netwerkfout zonder duidelijke SMTP-afwijzing blijft onzeker. Controleer bij twijfel de provider voordat je herstelt.

## Queue, retries en toekomstige deployment

Alle contactjobs gebruiken de bestaande **databaseverbinding** en uitsluitend queue **`contact-mail`**. Payloads bevatten de intentiereferentie en een dispatchtoken, geen aanvraagtekst. Atomair claimen en tokens per enqueue voorkomen normale dubbele verzending, inclusief oude achtergebleven jobs. Er blijft geen databasetransactie open tijdens SMTP.

Een SMTP-afwijzing met 4xx krijgt maximaal drie pogingen, met 60 en 300 seconden wachttijd. 5xx, een ongeschikt transport of voorbereidingsfouten worden `failed`; onzekere uitkomsten krijgen geen automatische retry. Logtransport en failover met log worden geweigerd. Arraytransport mag alleen met expliciete `CONTACT_ALLOW_TEST_TRANSPORT=true` én omgeving `local`/`testing`, en krijgt uitsluitend status `intercepted`.

Contact-SMTP gebruikt een sockettimeout van 10 seconden in de worker, zonder `.env` of algemene SMTP-instellingen te wijzigen. Jobtimeout: 60 seconden; databasequeue `retry_after`: bestaand 90 seconden; herstel van oude claims: 300 seconden. Op Windows ontbreekt PCNTL doorgaans en is de worker-timeout niet hard afdwingbaar; de SMTP-sockettimeout blijft actief. Voor de latere Linux-worker is PCNTL vereist. Deze verhouding volgt de [Laravel 13 queue-instructies](https://laravel.com/framework/docs/13.x/queues#timeout).

Eenmalig uitsluitend deze queue verwerken, bijvoorbeeld bij Kevins handmatige eindtest:

```powershell
php artisan queue:work database --queue=contact-mail --stop-when-empty --tries=1 --timeout=60
```

Deze opdracht gebruikt de echte `.env` en kan **echte mails versturen**. Hij is tijdens deze run niet op de ontwikkelqueue uitgevoerd. De queue heeft zelf één jobpoging; de opgeslagen intentie bepaalt de begrensde, afzonderlijke retries.

Voor deployment later: migratie normaal toepassen na backup; een bewaakte worker starten met `queue:work database --queue=contact-mail --sleep=3 --tries=1 --timeout=60`; Laravel `schedule:run` elke minuut uitvoeren. De toegevoegde schedule roept `contact:mail --dispatch` aan en voorkomt overlappende schedulerruns. Die pakt due pending en vergeten queued intenties op, en markeert oude processing-claims onzeker. Zet de gedeelde databasecache, queue, SMTP en logging klaar; controleer dagelijks `failed`/`uncertain`. Geen Redis, Horizon of algemene mailqueue-aanpassing. Op de VPS is niets uitgevoerd.

## Limieten en privacy

Standaard maximaal **12 nieuwe geldige aanvragen per IP per uur** en **3 per genormaliseerd e-mailadres per uur**. Identieke herhalingen van een opgeslagen poging kosten geen extra quota en plannen niets opnieuw in. E-mailadressen worden getrimd en naar kleine letters omgezet. Sleutels gebruiken HMAC-hashes; de cache bevat geen ruwe adressen/IP's. De quota en bijbehorende locks gebruiken de gedeelde databasecache. Ongeldige invoer en de honeypot slaan niets op; een opslagfout kan uit voorzorg wel quota verbruiken.

Aanpassen via `config/contact.php` of de overeenkomstige omgevingsvariabelen:

```dotenv
CONTACT_IP_LIMIT=12
CONTACT_EMAIL_LIMIT=3
CONTACT_LIMIT_SECONDS=3600
```

`CONTACT_CACHE_STORE` moet naar een Laravel-databasecachestore wijzen; een vluchtige store wordt geweigerd. De redelijke IP-ruimte laat meerdere bezoekers achter hetzelfde netwerk toe, terwijl de adreslimiet bevestigingsmisbruik beperkt. Bij een limiet blijft de tekst staan en verschijnt een wachttijd plus het mailalternatief.

De publieke privacytekst beschrijft nu de daadwerkelijk gebouwde aanvraagopslag, ontvangstbevestiging, technische sessiecookie en spambeperking. **Nog vóór publicatie bepalen:** de bewaartermijn voor aanvragen, mailstatussen, mailboxkopieën en backups, en de bijpassende privacyinformatie. Er is geen termijn verzonnen en er is geen automatische verwijdering toegevoegd.

**Aanvulling stap 10, 9 oktober 2026:** de uitgebreide gegevensinventarisatie, bijgewerkte privacyinformatie, registratieblokkering en standaard alleen-lezen `contact:prune` staan in [Privacy en publieke toegang](privacy-en-publieke-toegang.md). Bewaartermijnen zijn voorstellen; uitvoering vereist een expliciet vastgestelde configuratie én `--execute`. Geen automatische verwijdering ingeschakeld. De daar beschreven geïsoleerde preview op 8110 vervangt het bewijs van run 9 niet; daadwerkelijke inboxontvangst, Reply-To en SPF/DKIM/DMARC blijven de afzonderlijke open productiecontroles hieronder.

## Lokaal bewezen — afronding 3 oktober 2026

- De bestaande PHP-suite is met PHP 8.4.15 uitgevoerd: **174 tests**, inclusief **30 contacttests en 41 mailtests**. 173 slaagden direct. Eén transportvariant faalde door `MAIL_URL=''` in de tijdelijke controlehelper: Laravel interpreteert dit anders dan `null`. Alleen die helper is gecorrigeerd; daarna zijn alle vier varianten van de betrokken test geslaagd (8 assertions). Er blijft geen mislukte test open; de echte SMTP-configuratie en applicatiecode zijn hiervoor niet aangepast.
- Gelijktijdige PHP-processen: één aanvraag, twee intenties/taken en één normale acceptatie van een gedeelde intentie. Apart getest: validatie, honeypot, gedeelde IP/adreslimieten, idempotentieconflicten, rollback, after-commit, enqueuefout, oude dispatchtokens, begrensde retries, onafhankelijk herstel, onzekere uitkomsten, geweigerde log/failover en private publieke respons.
- De aparte teamopruimtest start ook de contactscheduler in een kindproces en logt hiervoor exitcode 1; de teamtest zelf slaagt. De `:memory:`-testdatabase met tabellen kan niet met dat kindproces worden gedeeld. De echte queue is daarbij niet benaderd. De contactdispatcher is afzonderlijk getest met aangemaakte testtabellen.
- Pint, PHPStan (0 fouten), Composer validate, frontend-lint, TypeScript, formatter en productiebuild geslaagd. De bestaande optionele fontaine-melding en melding over pluginlooptijden blokkeren de build niet; geen dependency toegevoegd.
- Browser uitsluitend Contact: **19 controles op 390 px en 18 op 1440 px**, waaronder Tab-focus op alle vier velden, blauwe rand/buitenring, rode veldfouten met verbonden tekst, bewaarde invoer, wachtstand/dubbelklikbescherming en succes met focus en statusrol. Geen horizontale overloop of JavaScript-excepties.
- De textarea blijft op beide breedtes **192 px (12rem)** hoog. **4576 tekens** zijn behouden; met toetsenbord is tekst aan begin en einde toegevoegd en binnen het veld gescrold. Ook sessieverloop met behoud van deze lange invoer en opnieuw versturen is gecontroleerd.
- Alle acht actuele screenshots hieronder zijn daadwerkelijk met de beeldviewer geopend en visueel beoordeeld. De eerdere screenshots [390 px](screenshots/contact/contact-390.png) en [1440 px](screenshots/contact/contact-1440.png) bewaren alleen de eerste oplevering van 26 september.
- Twee browseraanvragen en vier mailintenties zijn alleen in de nieuwe aparte preview verwerkt: alle vier `intercepted`, één poging per mail. Geen echte e-mail verzonden en de bestaande ontwikkelqueue niet verwerkt.

| Actuele beeldcontrole | 390 px | 1440 px |
| --- | --- | --- |
| Toetsenbordfocus | [Mobiel](screenshots/contact/contact-390-focus.png) | [Desktop](screenshots/contact/contact-1440-focus.png) |
| Validatiefouten | [Mobiel](screenshots/contact/contact-390-errors.png) | [Desktop](screenshots/contact/contact-1440-errors.png) |
| Lange tekst | [Mobiel](screenshots/contact/contact-390-long.png) | [Desktop](screenshots/contact/contact-1440-long.png) |
| Succesmelding | [Mobiel](screenshots/contact/contact-390-success.png) | [Desktop](screenshots/contact/contact-1440-success.png) |

Technische aansluiting: geïnstalleerde Laravel-/Inertia-/Wayfinder-code, [Laravel 13 Mail](https://laravel.com/framework/docs/13.x/mail), [Laravel 13 after-commit jobs](https://laravel.com/framework/docs/13.x/queues#jobs-and-database-transactions) en [Inertia 3 Forms](https://inertiajs.com/docs/v3/the-basics/forms).

## Nog open vóór productie

De bestaande mailtests bewijzen lokaal de MIME-opmaak, het originele CID-logo, de ontvangers en beide Reply-To-headers. Onderschepte mail of een opgeslagen status `accepted` bewijst geen daadwerkelijke mailboxontvangst.

| Controle | Bewijs / status |
| --- | --- |
| Eerdere losse SMTP-testmelding uit run 7–8 | Ontvangst in Outlook is in de eerdere opdracht als bevestigd vastgelegd; dit is niet hetzelfde als beide formuliermails. |
| Ontvangst van de aanvraagmelding én bezoekersbevestiging uit één formulierinzending | **Open:** bij deze afronding zijn geen beide ontvangen berichten of volledige berichtbronnen beoordeeld. |
| Reply-To van beide daadwerkelijk ontvangen formuliermails | **Open:** lokaal automatisch getest, nog te controleren in de echte berichten. |
| SPF, DKIM en DMARC van beide ontvangen formuliermails | **Open:** geen beoordeelde `Authentication-Results`; Received-regels, aanwezige DNS-records en SMTP-acceptatie zijn onvoldoende bewijs. |
| Webmailtoegang | Werkt volgens Kevin via Chrome; dit bewijst op zichzelf geen bezorging of mailauthenticatie. |
| Productieomgeving | Worker/scheduler, SMTP vanaf de VPS en de releasecontrole moeten nog worden uitgevoerd bij een afzonderlijk geautoriseerde deployment. |
| Bewaartermijn en privacyinformatie | Nog vast te stellen vóór publicatie, zie de paragraaf over privacy. |

## Kevin: later bewust één echte aanvraag testen

1. Beoordeel de geïsoleerde preview op **http://127.0.0.1:8109/contact**. Start voor een bewuste echte aanvraag daarna de gewone server en open **http://127.0.0.1:8098/contact**. Gebruik een nieuw PowerShell-venster met de projectmap en PHP 8.4.15 zoals bovenaan.
2. Voer eerst `php artisan contact:mail` uit. Er bestaan inmiddels ontwikkelgegevens: beoordeel bestaande aanvragen en mailstatussen voordat je een worker start. De worker verwerkt alle wachtende taken op `contact-mail`; verwerk niet onbedoeld eerdere aanvragen. Bekijk waar mogelijk eerst bestaande ontvangen berichten zodat een nieuwe verzendtest niet onnodig wordt herhaald.
3. Vul je eigen adres **kevin.webappbeheer@outlook.com**, een fictieve naam en fictieve aanvraaginhoud in. Verstuur **één keer**. De succesmelding zegt dat het bericht is ontvangen.
4. Voer `php artisan contact:mail` uit: controleer één nieuwe referentie en twee `queued`-regels. Neem de referentie over in `$contactReference` en controleer de inhoud met `php artisan contact:mail $contactReference --show`.
5. Voer bewust uit: `php artisan queue:work database --queue=contact-mail --stop-when-empty --tries=1 --timeout=60`. Controleer daarna `php artisan contact:mail $contactReference`. Beide mails horen `accepted` te tonen; dat is nog geen bewijs van bezorging. Bij fouten volg je het gerichte herstel hierboven, niet opnieuw het formulier insturen.
6. Controleer de melding in **info@kevinwebstudio.nl** en de ontvangstbevestiging in je **Outlook-adres**. Bekijk de goedgekeurde HTML-opmaak, het logo en de tekstvariant. Beantwoorden op de melding hoort naar je opgegeven Outlook-adres te gaan; Beantwoorden op de ontvangstbevestiging naar **info@kevinwebstudio.nl**.
7. Lees bij beide echte berichten `Authentication-Results`: SPF, DKIM en DMARC, inclusief aansluiting op het afzenderdomein. Noteer ontbrekende of falende resultaten voor de releasecontrole. Alleen Received-regels of alleen SMTP-acceptatie bewijzen dit niet. De gewone run-7–8-testmelding hoeft niet opnieuw verstuurd te worden.

Daadwerkelijke ontvangst van beide formuliermails, hun echte antwoordbestemmingen en authenticatieresultaten blijven open totdat het bovenstaande bewijs is vastgelegd. Er zijn geen lokale bouwblokkades. Contactformulier én interactieve onderwijsformulier-demo blijven vereist voor release 1; de demo volgt afzonderlijk.
