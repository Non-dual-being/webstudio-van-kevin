# Privacy en publieke toegang — stap 10

Lokale oplevering: **9 oktober 2026**, `C:\wamp64\www\kevin-webstudio`, branch `feature/public-visual-refinement`. Uitgangspunt was contactcommit `de0d44a440fd41a572d95b9ed66ef81b818452bc`; er is niets naar die commit teruggezet. PHP **8.4.15**.

**Technische voorbereiding klaar; nog geen vrijgave voor productie.** De publieke privacytekst, verwijzing bij het formulier, serverzijdige registratieblokkering en handmatige contactopruimfunctie zijn gebouwd en lokaal getest. Identiteit, leveranciersafspraken, bewaarkeuzes en productiecontroles moeten vóór de eerste release worden afgerond. De vijf beslissingen onderaan zijn daarvoor leidend.

Goedgekeurde vormgeving, logo, navigatie, formulierverwerking, mailinhoud, SMTP-configuratie, spambeperking en onafhankelijke mailtaken zijn behouden. De bestaande wijziging in `lokaal-ontwikkelen-en-git.md` en de ongetrackte instructie- en Simon-bestanden horen niet bij deze oplevering.

## Reikwijdte van het bewijs

Inventarisatie op basis van code, configuratiebestanden, migraties, geïnstalleerde frameworkcode en een nieuwe geïsoleerde browserpreview. **Geen bestaande aanvraaginhoud, mailboxinhoud, database-export of ontwikkellogs gelezen. Geen bestaande gegevens verwijderd of ontwikkelqueue verwerkt. Geen echte e-mail verstuurd.** Configuratiedefaults zijn geen bewijs van de latere VPS-instellingen.

De [contacthandleiding](contactformulier.md) en [zakelijke-mailhandleiding](zakelijke-mail.md) blijven de bron voor verzending en herstel. Een status `accepted` betekent SMTP-acceptatie, niet aantoonbare ontvangst. `intercepted` is alleen een onderschepte lokale/testverzending.

## Gegevensstromen

| Plaats | Aantoonbare verwerking en doel | Grens van de vaststelling |
| --- | --- | --- |
| `contact_submissions` | Naam, e-mailadres, optionele dienst, volledige berichttekst, interne referentie, tijdstippen en attempt-/session-/payloadhashes. Nodig voor beantwoorden, duurzame verwerking en dubbele-inzendingsbescherming. Geen IP of user-agent in deze tabel. | Velden met inhoud zijn niet door het model versleuteld; `hidden` voorkomt serialisatie, geen inzage door databasebeheerders. Database- en schijfbeveiliging bij deployment controleren. |
| `contact_mail_deliveries` | Twee intenties per aanvraag: melding en bevestiging. Status, pogingen/limiet, dispatchtoken, wacht-/start-/eindtijd en vaste foutcategorie. Relatie naar de aanvraag; foreign key met `RESTRICT`. | Ook metadata is tot een persoon herleidbaar via de aanvraag. Geen bewijs van inboxbezorging. |
| Sessies | Bij de standaard database-driver: sessie-ID, eventuele gebruiker-ID, IP-adres, user-agent, laatste activiteit en payload. Payload kan CSRF-token, formulierpoging/eigenaar, flashmeldingen, validatiefouten en tijdelijk afgewezen invoer (`_old_input`) bevatten. | Ongeldige invoer maakt geen contactaanvraag, maar kan dus wel tijdelijk in de sessie staan. Default `SESSION_ENCRYPT=false`: base64-opslag is geen versleuteling. Default inactiviteit 120 minuten; fysieke verwijdering is opportunistisch via een 2%-lottery, niet gegarandeerd na precies twee uur. |
| Databasecache en locks | Contactquota: HMAC-herkenningswaarden van IP en genormaliseerd e-mailadres, tellers en vervaltijd; lockeigenaar/vervaltijd. Standaard 12 aanvragen/IP/uur, 3/adres/uur, teller 3600 seconden, lock 15 seconden en vrijgave na verwerking. Authenticatie gebruikt daarnaast afgeleide login-/IP- en passkey-/sessiesleutels. | Hashes en tokens zijn **pseudonieme herkenningswaarden**, niet automatisch anoniem. Verlopen cachedata kan fysiek achterblijven totdat deze wordt benaderd/opgeruimd. Geen algemene `cache:clear` gebruiken als bewaarbeleid: dat wist ook actieve beveiligingslimieten. |
| Wachtrijen | Contact gebruikt de databaseverbinding en queue `contact-mail`. `SendContactMail` bevat delivery-ID en dispatchtoken; inhoud wordt pas bij verzending opgehaald. `jobs` bevat ook pogingen/reservering/planning. `job_batches` kan batchmetadata bevatten; contact gebruikt geen batches. | Andere starterkittaken/notifications kunnen wél persoonsinformatie bevatten. Queues zijn geen anonieme opslag. Normaal verwijdert een worker een afgeronde job. |
| Foutregistratie en logs | `failed_jobs`: verbinding, queuenaam, payload, exception/stacktrace en tijd. Eigen contactlogging gebruikt ID's en vaste foutcategorieën in plaats van berichtinhoud. Default logging is `stack` → `single`, niveau `debug`; `single` roteert niet vanzelf. | Frameworkexceptions, andere taken, proxy-/webserverlogs kunnen persoonsgegevens bevatten. Het configureerbare `daily`-kanaal heeft default 14 dagen, maar is daarmee nog niet ingeschakeld. Serverlogvelden, toegang en retentie blijven deploymentwerk. |
| Mailboxen | Zakelijke melding bevat de aanvraag; bevestiging bevat alleen de beperkte begroeting en geen oorspronkelijke berichttekst/dienst. Zakelijke mail loopt volgens de bestaande handleiding via TransIP. Ook de mailboxaanbieder van de geadresseerde verwerkt mail. | Mailboxmappen, prullenbak/spam, doorsturingen, gesynchroniseerde apparaten, providerlogs en providerback-ups zijn afzonderlijke kopieën. Locaties, subverwerkers en daadwerkelijke ontvangst zijn hier niet onderzocht. |
| Accounts en teams | Bestaande beheerdersaccounts bewaren naam, e-mail, wachtwoordhash, verificatie-/onthoudgegevens en eventueel 2FA/passkeys. Teams en uitnodigingen bewaren ledenrollen, adressen, codes en tijdstippen. | Geen klantportaal toegevoegd. Openbare registratie uitgeschakeld; bestaande accounts/uitnodigingsafhandeling behouden. Beheerderstoegang en accountretentie operationeel vaststellen. |
| Back-ups en bestanden | Een databaseback-up kan aanvragen, sessies, cache, queue- en accountgegevens bevatten. De eerdere lokale back-up staat vermeld in het contactrunbook. Mailpreviewbestanden zijn volgens de mailhandleiding fictieve voorbeelden in private opslag. | Geen back-up gemaakt of inhoud gelezen voor stap 10. Productieback-ups, eventuele exports en leverancierskopieën moeten nog worden geïnventariseerd. SQL-verwijdering is geen claim over forensisch wissen van databasepagina's, WAL, mailboxen of back-ups. |

Bronnen in de repository: contactmigratie, `StoreContactSubmission`, `ContactFormAttempt`, `ContactMailDispatcher`, `SendContactMail`, beide contactmodellen, `config/{contact,session,cache,queue,logging,filesystems}.php` en de gebruikers-/cache-/queuemigraties. Frameworkcontrole: `DatabaseSessionHandler` schrijft IP/user-agent; de exceptionhandler flasht afgewezen invoer; de databasecachestore ruimt verlopen waarden bij gebruik op.

## Cookies, browseropslag en verzoeken

De productiebuild is in een schoon Chrome-testprofiel bekeken op 390 en 1440 px, op Contact, Privacy en Login:

| Onderdeel | Lokale bevinding |
| --- | --- |
| Sessiecookies | Alleen `kevin_privacy_preview` en `XSRF-TOKEN`, beide circa 120 minuten en `SameSite=Lax`. Sessiecookie `HttpOnly`, XSRF-cookie leesbaar voor JavaScript. `Secure=false` in deze expliciete HTTP-preview; voor productie HTTPS en effectieve Secure-/domein-/proxyinstellingen controleren. De productiesessienaam hangt van de configuratie af. |
| Onthouden van een bestaand account | Broncode van `SessionGuard`: bij gekozen “Remember me” kan een `remember_…`-cookie worden gezet, met frameworkdefault 576000 minuten (400 dagen). Niet aangemaakt of getest in deze gastproef. Uitloggen/toegang intrekken en de uiteindelijke beveiligingsinstellingen horen bij accountbeheer. |
| Formuliergegevens | Invoer blijft tijdens validatiefouten in de pagina beschikbaar. In deze proef geen concepttekst in `localStorage`, `sessionStorage` of `history.state`. Inertia bewaart wel paginaprops/scrollinformatie in browsergeschiedenis; contactprops omvatten onder andere de formulierpoging en fouten. Browsergeheugen, geschiedenis en automatisch invullen zijn geen opslag met een door deze applicatie gegarandeerde wisdatum. |
| Overige browseropslag | Schone publieke sessies hadden geen local-/sessionStoragekeys, IndexedDB-databases, Cache Storage of serviceworkers. Broncode leest een bestaande `appearance`-voorkeur; wijzigen in het ingelogde gedeelte schrijft localStorage zonder eigen einddatum en een cookie van 365 dagen. `sidebar_state` wordt daar bij toggelen voor 7 dagen gezet. Deze voorkeuren zijn niet tijdens de publieke proef aangemaakt. |
| Externe verzoeken | Geen externe netwerkverzoeken vanuit de gecontroleerde pagina's. Instrument Sans kwam uit lokale `/build/assets/`-bestanden. Bunny wordt door de buildtool gebruikt, niet door bezoekers van deze build. Geen toegevoegde analytics, advertentietags, captcha, embeds of externe afbeeldingen. De EDPB-link is een gewone link die pas bij aanklikken naar de externe site gaat. |

Dit bewijst geen afwezigheid van hostingstatistiek, proxylogging, toekomstige CDN's of injecties op productie. Herhaal de controle op de uiteindelijke origin en na wijzigingen aan externe scripts. Cookies voor noodzakelijke formulierbeveiliging en gebruikersvoorkeuren afzonderlijk beoordelen onder het toepasselijke nationale cookierecht; het vestigingsland is niet uit het `.nl`-domein afgeleid.

## Privacyinformatie en grondslagen

De publieke pagina gebruikt begrijpelijk Nederlands en de bestaande layout. De formulierlink heet nu “privacyverklaring”. Geen toestemmingscheckbox, onbewezen bewaartermijn, verzonnen bedrijfsadres, hostingland of algemene juridische garantie toegevoegd.

De geraadpleegde officiële bronnen zijn gecontroleerd op **9 oktober 2026**:

- Voor noodzakelijke stappen op verzoek van een natuurlijke persoon richting een eigen opdracht past art. 6 lid 1 b AVG. Voor algemene vragen, contact namens een organisatie en misbruikbeperking is art. 6 lid 1 f het voorgestelde uitgangspunt; geen algemeen beroep op “overeenkomst” voor alle beveiliging. Gerechtvaardigd belang vereist een concrete noodzaak- en belangenafweging. [EDPB: rechtmatige verwerking](https://www.edpb.europa.eu/sme/be-compliant/process-personal-data-lawfully_en).
- Bij verzamelen moet informatie begrijpelijk beschikbaar zijn over verantwoordelijke/contact, doeleinden, gegevens, grondslag, ontvangers, bewaartijd of criteria, rechten en relevante doorgiften. De publieke tekst is voorbereid; ontbrekende identiteit en leveranciers-/doorgifte-informatie blijven een releasevoorwaarde. [Europese Commissie: informatieplichten](https://commission.europa.eu/law/law-topic/data-protection/information-business-and-organisations/obligations_en).
- Inzage, correctie, wissen/beperking, bezwaar en waar toepasselijk overdraagbaarheid zijn toegelicht; het zijn geen onbeperkte rechten. [EDPB: rechten](https://www.edpb.europa.eu/sme/be-compliant/respect-individuals-rights_en). De termijn voor een reactie op een rechtenverzoek is één maand; een toegestane verlenging moet binnen die maand worden gemeld. [EDPB: praktische vragen](https://www.edpb.europa.eu/sme/find-practical-info/faq_en).
- Bewaar alleen zolang het doel dat vereist en leg wis- of beoordelingsmomenten vast. De AVG schrijft geen algemene “contactformuliertermijn” voor. De onderstaande aantallen dagen/maanden zijn een voorstel voor deze kleine website, geen wettelijke termijn. [Europese Commissie: opslagbeperking](https://commission.europa.eu/law/law-topic/data-protection/information-business-and-organisations/principles-gdpr_en#storage-limitation).

Voorlopige belangenafweging: bezoekers verwachten antwoord en formulierbeveiliging; naam/e-mail/bericht maken antwoord mogelijk, beperkte quota verminderen misbruik. Minder ingrijpend dan captcha, profilering of registratie: beperkte gegevens, HMAC-sleutels, geen marketinghergebruik en korte technische opslag. Resterende nadelen: blokkades bij gedeelde IP's, vrije tekst met onnodige privégegevens, extra mailboxkopieën en ruwe IP's in sessies. De bezoeker houdt een rechtstreeks mailalternatief. Kevin moet noodzaak, proportionaliteit en de uiteindelijke bewaarkeuzes vaststellen; deze schets is geen afgeronde complianceverklaring.

## Bewaarvoorstel — nog niet geactiveerd

| Categorie | Voorstel en startmoment | Reden en uitvoerbare opruimwijze |
| --- | --- | --- |
| Technische websitekopie incl. hashes en mailstatussen | **90 dagen vanaf de laatste afgeronde mailtaak**; aanvraag zelf moet ook ouder zijn. | Tijdelijk herstel/onderzoek in de eerste release, los van verdere correspondentie. Na drie maanden evalueren of korter volstaat. Eerst `contact:prune`; pas na vaststelling expliciet `--execute`. Beide intenties en aanvraag samen verwijderen. |
| Onopgeloste aanvragen | Vanaf **30 dagen na ontvangst** wekelijks beoordelen; uiterlijk op **90 dagen** afhandeling, gecontroleerde afsluiting of gemotiveerde verlenging met nieuwe einddatum kiezen. | `contact:prune --days=30` rapporteert aantallen. Gebruik uitsluitend bevoegd beheer voor inhoudelijke opvolging. `failed`, `uncertain`, lopende of ontbrekende intenties worden nooit door prune gewist. Definitief afgebroken gevallen vragen een afzonderlijk goedgekeurde, gerichte transactie na controle van alle taken; niet kunstmatig op `accepted` zetten. |
| Sessies en tijdelijke afgewezen invoer | **120 minuten inactiviteit**, fysieke opruiming uiterlijk bij een dagelijkse ronde. | Na akkoord een gerichte taak voor verlopen `sessions.last_activity` in batches; geen actieve sessies afmelden. De bestaande lottery alleen biedt geen vaste fysieke einddatum. |
| Contactquota en locks | Teller **1 uur vanaf de eerste hit in het venster**; locks maximaal **15 seconden**; verlopen resten uiterlijk in een dagelijkse ronde. | Actieve limieten intact laten. Alleen verlopen rijen met de toepasselijke cacheprefix/expiration opruimen, locks pas na expiry. Deze aanvullende taak is nog niet gebouwd/geactiveerd. |
| Jobs, failed jobs, batchmetadata | Succesvolle jobs direct na verwerking; wachtende taken vanaf **24 uur** beoordelen. Fout-/afgeronde batchmetadata **30 dagen vanaf fout/afronding**, na beoordeling. | Geen lopende taken weggooien. Los storing/deliverystatus eerst op; verwijder daarna gericht beoordeelde failed-job-ID's via queuebeheer. Andere queues apart beoordelen. Geen globale queue-clear als privacymaatregel. |
| Applicatie- en webserverlogs | **14 dagen vanaf logdatum**, incidentrelevante fragmenten alleen langer met vastgelegde reden/einddatum. | Bij deployment daily-rotatie en providerrotatie instellen; debug uit en inhoud/tokenlogging vermijden. Geen bestaande logs gewist in deze stap. |
| Mailcorrespondentie zonder opdracht | **6 maanden vanaf het laatste inhoudelijke contact**. | Redelijke ruimte voor hervatten van een vraag. Daarna periodiek mailbox, verzonden items, spam/prullenbak en gesynchroniseerde kopieën nalopen. Onderliggende providerretentie apart vaststellen. |
| Latere klantadministratie | Tijdens de opdracht; daarna de **daadwerkelijk toepasselijke wettelijke/contractuele termijn vanaf het relevante afsluitmoment**. | Alleen noodzakelijke correspondentie overbrengen. Land/rechtsvorm en administratiecategorie bepalen eerst de termijn; niet de hele contactdatabase als fiscale administratie bewaren. Concrete termijnen met administrateur vaststellen. |
| Back-ups | Voorstel **30 dagen rollend vanaf aanmaak**. | Beperkte, beveiligde generaties automatisch laten verlopen na akkoord. Verwijderverzoeken en latere wisacties na herstel opnieuw toepassen vóór hergebruik. Toegang, versleuteling en leveranciersretentie bevestigen. Prune raakt geen back-ups. |
| Beheeraccounts en browservoorkeuren | Toegang intrekken bij einde bevoegdheid; accountdata binnen **30 dagen daarna** beoordelen. Voorkeuren: bestaande cookie 365/7 dagen, localStorage tot wissen/wijzigen. | Sessies/passkeys en rollen gericht intrekken; geen bestaande accounts verwijderen in deze taak. Lokale voorkeuren via browserinstellingen te wissen, geen aanvraaginhoud daarin opslaan. |

Een verzoek om inzage/beperking, een concreet geschil of incident kan een gerichte bewaarplicht/uitzondering vragen. **Voer de batchopruiming dan niet uit voor een verzameling met te bewaren gegevens.** Er is geen legal-holdveld of uitzonderingslijst ingebouwd. Leg een afzonderlijke afhandeling vast; stel opruimen niet voor onbepaalde tijd uit zonder herbeoordeling.

## Bediening van de contactopruimfunctie

Nieuwe bestanden: `app/Console/Commands/PruneContactSubmissions.php` en `app/Support/ContactRetention.php`. Geen migratie en geen schedule toegevoegd. `CONTACT_RETENTION_DAYS` is standaard `null` in de configuratie; de lokale `.env` is niet gewijzigd. Er is door deze taak dus geen bewaarbeleid aangezet.

Met WAMP PHP 8.4.15, vanuit de repository:

```powershell
# Alleen rapporteren: de voorgestelde 90 dagen of de vastgestelde configuratie.
& 'C:\wamp64\bin\php\php8.4.15\php.exe' artisan contact:prune

# Alleen simuleren met een andere beoordelingsgrens.
& 'C:\wamp64\bin\php\php8.4.15\php.exe' artisan contact:prune --days=30
```

Deze commando's zijn in deze taak **uitsluitend met geïsoleerde testconfiguratie** uitgevoerd. Een gewone projectterminal leest de bestaande `.env`; gebruik die niet voor automatische proeven.

Na vaststelling en afzonderlijke operationele voorbereiding kan een beheerder `CONTACT_RETENTION_DAYS` configureren, effectieve config controleren, eerst de dry-run beoordelen en daarna `contact:prune --execute` uitvoeren. `--execute` zonder geldige configuratie of in combinatie met `--days` wordt geweigerd. Er is geen impliciete activering door een scheduler.

Veiligheidsregels van de implementatie:

- Alleen exact twee bekende intenties, beide `accepted` of `intercepted`, met niet-lege eindtijden ouder dan de grens. Recente afronding verlengt de technische bewaartijd. Onopgelost wordt als afzonderlijk totaal gerapporteerd; geen inhoud, referenties, hashes of adressen in uitvoer/logs.
- Contactjobs, inclusief gereserveerde jobs, of contacttaken in `failed_jobs` blokkeren uitvoering van de hele batch. De functie leest geen jobpayload en verwijdert geen taken. Afwijkende/oncontroleerbare queueopslag weigert uitvoering; de databasequeue en failed-jobopslag moeten dezelfde databaseverbinding gebruiken.
- Maximaal **500 aanvragen per expliciete uitvoering**, in één transactie. Vóór verwijderen worden ouderrij en deliveryrijen opnieuw gecontroleerd en vergrendeld. Eerst kinderen, dan ouder wegens `RESTRICT`. Bij een databasefout wordt de batch teruggedraaid; concurrentiefouten worden begrensd herprobeerd. Een stale worker kan een terminale of verwijderde delivery niet claimen.
- De bestaande worker verzendt buiten een databasetransactie. Een actieve `processing`-aanvraag blijft daarom altijd staan; een net afgeronde taak voldoet niet aan de leeftijdsgrens. Mailverwerking zelf is niet gewijzigd.
- De echte parallelproef gebruikt SQLite met twee prune-processen en een daadwerkelijk in `SendContactMail` wachtende testworker. Eén oude complete aanvraag verdwijnt één keer, de actieve aanvraag blijft inclusief beide intenties staan. Een toekomstige andere database-engine vereist een herhaling met fictieve gegevens vóór ingebruikname.

Een rustig onderhoudsmoment verdient de voorkeur: voorkom nieuwe dispatch, laat al lopende workers normaal afronden en controleer fouten. **Geen workers hard stoppen of queue leeggooien om de blokkade te omzeilen.** `accepted` alleen bewijst geen inboxkopie; dat moet operationeel vóór het eerste opruimen zijn afgedekt. Het commando verwijdert uitsluitend de technische websitekopie en de gekoppelde mailstatussen.

## Publieke toegang

`Features::registration()` is verwijderd uit `config/fortify.php`. De werkelijke Fortify-routes voor zowel GET als POST `/register` ontbreken. Ook JSON-posts en verzoeken met een uitnodigingscode leveren 404 op en maken geen gebruiker/team aan. De aanmeldlink is uit Login verwijderd. De ongebruikte starterkitregistratiepagina blijft behouden met een vast formulierendpoint; er is geen gegenereerde Wayfinder-registratieroute meer. Het ontbreken van de serverroute is de beveiliging.

De loginrespons deelde voorheen bij een geldige uitnodigingscode de teamnaam met gasten. Die prop en weergave zijn verwijderd. Na inloggen blijft de bestaande uitnodigingsafhandeling werken.

De route-inventarisatie en tests bevestigen auth-beveiliging voor dashboard, instellingen, teams, leden en uitnodigingen, zowel lezen als muteren. Gasten krijgen loginredirects of JSON 401; publieke paginaprops bevatten geen account-/team-/aanvraaggegevens. Er is geen publieke contactlees- of exportroute. Bestaande login, wachtwoordherstel, verificatie, 2FA, passkeys en teamautorisatie blijven aanwezig en vallen onder de bestaande testsuite.

De frameworkroutes `/storage/{path}` hebben geen zichtbare auth-middleware in `route:list`, maar de geïnstalleerde handlers vereisen voor private opslag een geldige tijdelijke handtekening (ook voor upload). Een ongetekend fictief pad gaf in de lokale browserproef 403. Er is geen publieke contactdownloadlink. Public documentroot, symlinks, bestandsrechten en tijdelijke URL-uitgifte op de VPS blijven apart te controleren.

## Uitgevoerde controles

- PHP 8.4.15 expliciet gebruikt; PHPUnit met SQLite `:memory:`, aparte tijdelijke SQLite-bestanden voor echte parallelprocessen. Mailfake/arraytransport; geen SMTP. Preview heeft eigen database, sessiecookie, cacheverbinding, queueverbinding, logs en gecompileerde views.
- **198 tests geslaagd, 1333 assertions**: volledige suite inclusief bestaande zakelijke-mail-/contacttests, registratieblokkering, gasttoegang, veilige dry-run, echte fictieve verwijdering met FK's, cutoff/recente afronding, onopgeloste statussen, queueblokkades, rollback, redactie van fouten en concurrerende workers. De eerste suite had één tijdelijke Vite-fontbestandsfout door een tegelijk lopende build; de suite is na de build volledig geslaagd.
- De al bestaande teamopruimtest start de contactscheduler in een kindproces zonder gedeelde `:memory:`-tabellen; dat logt exitcode 1 terwijl de teamtest slaagt, zoals al vastgelegd in run 9. Geen bestaande database of echte queue is daarbij benaderd. De nieuwe contactopruiming staat niet in de scheduler.
- Pint, PHPStan (**0 fouten**), Composer validate, `pnpm format:check`, `lint:check`, `types:check` en productiebuild geslaagd. Alleen de bestaande niet-blokkerende meldingen over optionele `fontaine` en pluginlooptijd in de build. Geen dependency gewijzigd.
- Chrome: **9 pagina-/focuscontroles en 6 toegangsproeven per breedte**, op **390 × 844** en **1440 × 1000**. Privacy, Contact en Login gecontroleerd; linkbediening met Tab/Enter, validatiefout met focus, oorspronkelijke logo, geen horizontale overflow of JavaScript-excepties. GET/POST registratie 404, private JSON-routes 401, contactdetail 404 en ongetekende storage 403.
- Alle **14 screenshots daadwerkelijk geopend en visueel beoordeeld**: volledige pagina's plus details van tekst en toetsenbordfocus. Bewijs in [screenshots/privacy](screenshots/privacy): [privacy 390](screenshots/privacy/privacy-390.png), [privacy 1440](screenshots/privacy/privacy-1440.png), [formulierlink 390](screenshots/privacy/contact-link-390.png), [formulierlink 1440](screenshots/privacy/contact-link-1440.png), [login 390](screenshots/privacy/login-390.png), [login 1440](screenshots/privacy/login-1440.png).
- Na de browserproef: **0 aanvragen, 0 deliveries en 0 jobs** in de nieuwe previewdatabase; alleen fictieve ongeldige formulierinvoer gebruikt. Dry-run toont geen kandidaten en een niet-ingesteld bewaarbeleid. Echte verwijdering uitsluitend in de fictieve PHPUnit-databases.

Preview: **http://127.0.0.1:8110/privacy**, formulier **http://127.0.0.1:8110/contact**. De eigen PHP-preview blijft beschikbaar zolang dat lokale proces draait; geen worker actief. Helpers, browserprofiel, gesaneerde browserrapportage en testlogs staan buiten Git in `C:\Users\kevin\AppData\Local\Temp\kevin-privacy-run10-20261009`. De HTTP-preview gebruikt arraymail en is geen productiecontrole.

## Vijf beslissingen voor Kevin vóór release

1. **Verantwoordelijke en toepasselijk land:** bevestig de juridische naam/rechtsvorm, het vestigingsland en de te publiceren contactgegevens achter Kevin Webstudio. Werk daarna de definitieve verantwoordelijke in de privacyinformatie bij; bepaal of aanvullende vertegenwoordiger-/FG-informatie van toepassing is. Het domein bewijst geen land.
2. **Leveranciers en kopieën:** bevestig VPS-/hostingpartij, TransIP-afspraken, subverwerkers, opslag-/toegangslocaties, verwerkersovereenkomsten, eventuele internationale doorgiften en bijbehorende waarborgen. Neem mailboxclients, doorsturingen, logs en back-ups mee. Vul de publieke tekst aan waar deze feiten dat vereisen.
3. **Bewaarbeleid en uitvoering:** keur de tabel goed of pas de termijnen aan; wijs een verantwoordelijke en vaste opruim-/beoordelingsmomenten aan. Regel uitzonderingen, definitief afgebroken aanvragen, mailboxwissen en herstel uit back-ups. Pas daarna configureren/uitvoeren; automatische contactverwijdering is nu uit.
4. **Grondslag en rechtenprocedure:** stel de concrete belangenafweging vast. Bepaal wie `info@kevinwebstudio.nl` op privacyverzoeken bewaakt, proportioneel identiteit controleert, binnen één maand antwoordt en relevante ontvangers/kopieën betrekt. Bewaar alleen minimale bewijsinformatie over afhandeling; geen standaard identiteitskopieën vragen.
5. **Go/no-go voor productie:** wijs de uitvoerder aan voor HTTPS/cookies, private database/bestanden, route-/configcache, gasttoegang, logrotatie en de uiteindelijke browsernetwerkcontrole. Plan apart de bewuste echte mailproef: ontvangst van **beide** formuliermails, juiste Reply-To en beoordeelde SPF/DKIM/DMARC-`Authentication-Results`. Deze punten blijven **open**; Chrome-webmailtoegang, DNS of SMTP-acceptatie zijn daarvoor geen bewijs. Geen deployment uitgevoerd in stap 10.
