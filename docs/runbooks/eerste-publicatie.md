# Eerste publicatie — voorbereiding run 13A

**Lokaal voorbereid, niet gepubliceerd.** Op 24 oktober 2026 is niets gedeployd, gemerged, aan DNS/certificaten of VPS-services veranderd en geen echte mail verstuurd. Deze handleiding is het uitvoerplan voor een **later afzonderlijk vrijgegeven publicatierun**. Stop bij iedere mislukte controle; een lokaal gerenderd bestand is geen actieve of bewezen serverconfiguratie.

## Basis en vastgestelde toestand

Repository `Non-dual-being/webstudio-van-kevin`, branch `feature/public-visual-refinement`. Begincommit `642cae38e806373f1306f70b7663a9a5284435ae`; origin feature stond daarop, origin main op `42063243033ac76106f87f4559ab30736188a852`. De oplevercommit van deze run staat in Git, herkenbaar aan `chore: prepare first website and education demo release`. Kies bij publicatie opnieuw een expliciete volledige SHA. README en `lokaal-ontwikkelen-en-git.md` hadden bestaande wijzigingen; instructiebestanden waren ongetrackt. Die zijn behouden en uitgesloten. Geen ander actief PHP/Node/Git-projectproces gevonden bij de begincontrole; geen bestaand previewproces gestopt.

| Onderdeel | Status | Bewijs / grens |
| --- | --- | --- |
| Hoofdsite Laravel/Vue, contact, publieke toegang | Gereed als lokale basis | [Contact](contactformulier.md), [mail](zakelijke-mail.md), [privacy/toegang](privacy-en-publieke-toegang.md); geen productiebevestiging |
| Vue/PHP-onderwijsdemo met 100 fictieve scholen | Gereed als lokale basis | [Demo](../../demos/onderwijsformulier/README.md), [dashboardcontrole](../demos/onderwijsdashboard-demo-controle.md), beide bronmanifesten |
| Publicatieconfiguratie, lokale build en controles | Voorbereid | `deploy/` hieronder; geen auto-activatie |
| Eigen accounts `kw-site` / `kw-demo`, eigen FPM-masters | Voorstel | Nog niet aangemaakt; gescheiden van gedeelde `php8.5-fpm` |
| Nieuwe mappen | Vrij bij inventarisatie | `/var/www/kevin-webstudio`, `/var/www/onderwijsdemo`, `/etc/kevin-webstudio` bestonden niet |
| Server | Alleen-lezen bevestigd via bestaande SSH-alias | `deploy@vps-75778280`, IPv4 `146.59.231.208`, IPv6 `2001:41d0:305:2100::69c2` |
| Runtime VPS | Alleen-lezen bevestigd | Nginx 1.28.3, CLI/FPM PHP 8.5.4; huidige gedeelde socket `/run/php/php8.5-fpm.sock`; systemd |
| Tools VPS | Alleen-lezen bevestigd | Composer 2.9.5, Node 22.22.1, pnpm 10.34.4; Certbot en rsync aanwezig; `setfacl` niet gevonden |
| Extensies CLI VPS | Aanwezig | o.a. ctype, curl, dom, fileinfo, filter, hash, iconv, intl, json, mbstring, openssl, PDO/pdo_sqlite, session, sqlite3, tokenizer, xml, zip, pcntl, posix |
| Certificaten en beheerdersrechten | Nog niet volledig gecontroleerd | `sudo -n` vraagt interactief om authenticatie. Geen wachtwoord gevraagd of gelezen; certificaatmappen en één afgeschermde vhost niet ingezien |
| Bestaande sites | Behouden | 7 sites-enabled: annetflowers-demo, columncollection-paulus, columncollection-scriptorium, desalidakeuken.nl, descheppersimon, truckslideshow, vandenheuvel-webshop |
| Vernieuwing certificaten | Bestaande mechanismen gezien, werking niet bewezen | Zowel `certbot.timer` als `snap.certbot.renew.timer` vermeld. Eerst beheerder laten vaststellen welke installatie de certificaten beheert; geen nieuwe algemene timer toevoegen |
| DNS web | Wijst nog elders | apex A `86.105.244.1`, AAAA `2a01:7c8:e100:1::50a0`; www CNAME naar apex; demo A `86.105.244.1` (mogelijk wildcard, zone nog te bekijken) |
| Publieke demo-URL / toestemming / privacybesluiten | Open | Geen claim dat voorgesteld subdomein al correct werkt; vijf besluiten onderaan |

GeoFortForm4 is niet gewijzigd of gestart; geen bron-.env, echte aanvragen, database, exports of mailboxinhoud gelezen. De geselecteerde bronregels worden niet opnieuw geïmporteerd. De bronmanifesten blijven de herkomstbron; fictieve scholen geven geen publicatierecht op die code.

## Runtime en buildkeuze

`composer.json` zegt `php ^8.3`, maar `composer.lock` bevat Laravel **13.22.0** en Symfony **8.1.x** met **PHP >=8.4.1**. PHP 8.3 is dus onvoldoende voor dit lockbestand. De huidige PHP 8.5-serie valt binnen de vastgelegde bereiken; dat is op zichzelf geen runtimetest. De demo heeft geen Composer/Laravel-runtime: alleen geselecteerde PHP-code, `src`, `server` en gebouwde `dist`.

WAMP bevat nu PHP **8.5.12** en **8.2.35**, niet meer het historische 8.4.15-pad. Voor deze run is de officiële Windows NTS **8.4.15** uitgepakt in `%TEMP%/kevin-release-13a-20261024/php84`; WAMP en PATH zijn niet globaal gewijzigd. De demo-starthelper blijft exact 8.4.15 eisen. Gebruik lokaal zo nodig `DEMO_PHP` naar die aparte binary; wijzig de controle niet om 8.5 toe te laten. Op productie wordt die ontwikkelhelper helemaal niet gestart.

Build **lokaal/in een geïsoleerde bouwomgeving** met Node **22.23.1**, pnpm **11.5.2** (hoofdsite, `pnpm-lock.yaml` v9), npm **12.0.2** (demo, eigen `package-lock.json`) en PHP **8.4.15** voor de Wayfinder-generatie. Hoofdsite Vite 8.1.5, demo Vite 7.3.7 verlangen in de Node 22-lijn minimaal 22.12. Gebruik `pnpm install --frozen-lockfile` en `npm ci`, nooit updates. Het script stopt bij andere bouwtoolversies; gebruik een taakgebonden toolmap als versies ontbreken, vervang geen globale servertools.

`deploy/prepare.mjs` exporteert uitsluitend een gekozen commit naar een **nieuwe map buiten de checkout**, installeert vastgelegde dependencies, bouwt beide apps en maakt `site.tar.gz`, `demo.tar.gz` en SHA-256-bestandsmanifest. Geen ontwikkel-.env/database/accounts/jobs, `public/hot`, `public/storage`, storage, node_modules of testdata in de runtimepakketten. Composer draait zonder scripts; alleen package-discovery wordt apart onder expliciete fictieve arraymail/in-memoryconfig uitgevoerd. Geen scheduler of worker. De demo bevat alleen `dist`, `server`, `src`. Windows-vendor moet vóór activering opnieuw met `composer check-platform-reqs --no-dev` onder de gekozen Linux-PHP worden gecontroleerd; geen platformcheck omzeilen.

**Releasevoorwaarde:** de gerichte lokale 8.5-tests vervangen geen Linux **8.5.4/FPM**-proef. In de volgende run: vóór publieke activering de release in een afgeschermde testomgeving met eigen fictieve DB/cache/sessie/queue, arraymail en zonder SMTP-geheimen controleren. Controleer daar ook PCNTL-timeout, dedicated sockets, chroot, POST-parsing, HTTPS-cookies en OS-rechten. Geen installatie of proefdeployment daarvoor in deze voorbereidingsrun.

## Concrete bestanden en bestemming

Alles staat centraal in [deploy](../../deploy). `.tpl` is een concept; nooit rechtstreeks installeren. `render.mjs` weigert niet-beoordeelde/onvolledige waarden, onverwachte sleutels, output in de repo en bestaande outputmappen. Het schrijft alleen lokale bestanden en activeert niets. De `reviewed`-waarde betekent beoordeling van templatewaarden, geen toestemming voor livegang.

| Bestand | Doel / latere bestemming |
| --- | --- |
| `release.example.json`, `render.mjs` | Niet-geheime invoer en lokale renderer; beoordeelde kopie buiten Git |
| `prepare.mjs` | Commit exporteren, locked builds, twee minimale runtimepakketten en manifest |
| `check.mjs` | Stopvoorwaarden renderer en samenhang router/FPM/queue/timer controleren |
| `templates/kw-http-bootstrap.conf.tpl` | Tijdelijke ACME-only HTTP-host, `/etc/nginx/sites-available/kw-http-bootstrap` |
| `templates/kw-sites.conf.tpl` | Definitieve drie TLS-hosts, `/etc/nginx/sites-available/kw-sites`; alleen na certificaten |
| `templates/kw-demo-fastcgi.conf.tpl` | `/etc/nginx/snippets/kw-demo-fastcgi.conf` |
| `templates/fpm-site.conf.tpl`, `fpm-demo.conf.tpl` | `/etc/kevin-webstudio/`; zelfstandige FPM-configs, **niet** in gedeelde pool.d |
| `templates/kw-*.service.tpl`, `kw-scheduler.timer.tpl` | `/etc/systemd/system/`; alleen de expliciet genoemde nieuwe units |
| `templates/site.env.example.tpl` | Invulvoorbeeld, privé uitwerken tot `/var/www/kevin-webstudio/shared/.env` |
| `templates/kw-logrotate.tpl` | Alleen gebruiken als bestaande `/etc/logrotate.d/nginx` deze logs niet al dekt; anders bestaande dekking voor deze logs gericht laten beoordelen |
| `backup-sqlite.php` | Consistente SQLite-back-up via backup-API, bestaande bestemming wordt geweigerd |
| `mail-health.php` | Alleen aantallen failed/uncertain/processing/achterstallig/failed contactjobs; geen dispatch, geen inhoud; exit 2 bij aandachtspunt, 1 bij controlefout |

## Isolatie en HTTP-contract

Hoofdsite documentroot is uitsluitend `current/public`. Alleen `index.php` wordt uitgevoerd, andere PHP-paden en private bestandssoorten/directories worden geweigerd. Geen `storage:link`: deze site heeft geen publieke uploads nodig. Runtime-.env, contactdatabase, logs en back-ups staan in private `shared`. CSRF, databasequota en uitgeschakelde registratie blijven in de applicatie. Nginx levert direct TLS, zet FastCGI `HTTPS on`; geen nieuwe reverse proxy en dus geen brede trusted-proxyconfiguratie.

Demo draait als **kw-demo**, zonder groepslidmaatschap van kw-site/www-data/deploy. De eigen FPM-master zet workers in een **chroot naar de minimale demo-release**. Het root-filesystem bevat daar geen `.env`, andere websites, `/etc`, host-`/tmp`, database, mailclient of shells. Code is root-owned, alleen leesbaar voor kw-demo. systemd geeft alleen de socketdirectory schrijfrecht, privé netwerk en uitsluitend AF_UNIX. Dit voorkomt afhankelijkheid van de nu deels world-readable rechten van andere websites; alleen `open_basedir` of een subdomein was onvoldoende geweest. Geen wijziging aan rechten van andere sites nodig. De hoofdsite krijgt eveneens eigen account en eigen FPM-master; de gedeelde php8.5-fpm-service blijft intact.

De bestaande router gebruikt `REQUEST_URI`, `QUERY_STRING`, `REQUEST_METHOD`, `CONTENT_TYPE`/`CONTENT_LENGTH` en PHP `$_POST`. Hij retourneert geen `false` om de ingebouwde server willekeurige bestanden te laten serveren. Nginx laat uitsluitend `/`, beperkte `/assets/*.js|css|woff|woff2` en de **negen exact benoemde API-routes** door naar vast **`/server/router.php` binnen de chroot**. Geen clientpad als SCRIPT_FILENAME, PATH_INFO, cookies/Authorization-headers naar demo, wildcard-CORS, bronrouter of oude API. Assets gaan ook via die router: zo blijven zijn CSP/no-store/referrer/noindex-headers behouden. De router controleert API-methodes en queries zelf. Nginx begrenst bodies op 32 KiB, ook bij chunked requests; PHP behoudt dezelfde grens. De demo-FPM sluit uploads, automatische sessies, URL-includes en uitvoer-/mailfuncties uit. `clear_env=yes` en geen `DEMO_TODAY`: productie gebruikt Europe/Amsterdam en de echte datum. Tabstatus, maximaal 120 records en resetgedrag zijn ongewijzigd.

Nginx-toegangslogs bevatten alleen tijd, host, methode, status, bytes en duur; geen IP, URI/query, referer, cookies, user-agent of body. FPM/Nginx-foutuitvoer is voor deze nieuwe hosts uitgeschakeld om ruwe requestdetails te vermijden; storingen onderzoeken via statuscodes, service-exitstatus en een gecontroleerde fictieve reproductie. Applicatie `daily` op warning, voorstel 14 dagen. Framework-failed-jobs kunnen wel persoonsgegevens/exceptioninformatie bevatten: beperkte toegang en afzonderlijke beoordeling blijven nodig. Geen algemene journald-, logrotate- of providerinstellingen gewijzigd. Logretentie is nog een te bevestigen beleidskeuze.

## Volgende publicatierun — in deze volgorde

### 1. Vrijgave, inventarisatie en codekeuze

Rond de vijf besluiten onderaan af. Laat een beheerder de ontbrekende root-only feiten bekijken; deel geen sleutelinhoud, .env of ruwe logs. Herbevestig hostnamen/lege doelpaden en beschikbare ruimte. Alleen-lezen Linux-controles:

```bash
set -euo pipefail
hostname; id; df -h /var/www; free -m
php8.5 -v; php-fpm8.5 -v; nginx -v
sudo grep -hE '^\s*(server_name|ssl_certificate)\s' /etc/nginx/sites-enabled/*
sudo find /etc/letsencrypt/live -maxdepth 1 -mindepth 1 -type d -printf '%f\n'
sudo grep -E 'daily|weekly|rotate|/var/log/nginx' /etc/logrotate.d/nginx
systemctl list-timers --all --no-pager
sudo crontab -l # alleen roosterregels beoordelen; geen secrets laten opnemen
command -v certbot; certbot --version
getent passwd kw-site kw-demo || true
```

Controleer welke Certbot-installatie/renewaltimer de bestaande certificaten beheert en kies diezelfde installatie. Laat dubbele bestaande renewalconfiguratie buiten deze release; neem alleen de nieuwe certificaten in het bestaande beheerde mechanisme op. Leg bestaande hostnamen en hun HTTP/TLS-respons vast om later te vergelijken. Bestaande sudo-toegang vereist mogelijk interactieve authenticatie door Kevin; geen wachtwoord in chat of scripts.

Voorstel productiebranch: na review een PR `feature/public-visual-refinement` → `main`, verplichte controles laten slagen, dan mergen en de resulterende SHA expliciet kiezen. Nu is **niet** gemerged. Gebruik een schone afzonderlijke checkout bij conflicten; neem lokale README/runbook/instructies niet per ongeluk mee. Controleer opnieuw `git ls-remote`; een oude `main` is geen releasebron met demo.

### 2. Privé back-up en lokale locked build

Eerste publicatie heeft nog geen productiedatabase om te back-uppen. Maak vóór serverconfiguratiewijzigingen een root-only kopie van de **betrokken** bestaande config, DNS-waarden en symlinkdoelen; registreer dat voor herstel. Latere releases: back-up bestaande database met onderstaande API, plus private .env/APP_KEY en uploads indien die later bestaan. Bewaar deze buiten Git, beperkt toegankelijk en versleuteld buiten de VPS volgens het afgesproken beleid. Kopieer nooit enkel een actieve SQLite-file terwijl WAL wordt gebruikt.

PowerShell, lokaal (nieuw extern outputpad kiezen, geen bestaande map overschrijven):

```powershell
$ErrorActionPreference = 'Stop'
Set-Location -LiteralPath 'C:\wamp64\www\kevin-webstudio'
function Assert-Exit { if ($LASTEXITCODE -ne 0) { throw "Opdracht mislukt: $LASTEXITCODE" } }
git status --short; Assert-Exit
git ls-remote origin refs/heads/main refs/heads/feature/public-visual-refinement; Assert-Exit
$ReleaseSha = Read-Host 'Volledige beoordeelde release-SHA'
$ReleaseOutput = Join-Path $env:TEMP ('kw-release-' + $ReleaseSha)
$BuildPhp = Join-Path $env:TEMP 'kevin-release-13a-20261024/php84/php.exe'
node deploy/check.mjs; Assert-Exit
node deploy/prepare.mjs $ReleaseSha $ReleaseOutput $BuildPhp; Assert-Exit
# Maak een kopie van deploy/release.example.json BUITEN Git.
# Controleer paden; vul beide certificaatnamen in; reviewed=true na templatebeoordeling.
$ReviewedConfig = Read-Host 'Pad naar beoordeelde niet-geheime configuratie'
node deploy/render.mjs $ReviewedConfig (Join-Path $ReleaseOutput 'rendered'); Assert-Exit
Get-FileHash (Join-Path $ReleaseOutput 'site.tar.gz') -Algorithm SHA256
Get-FileHash (Join-Path $ReleaseOutput 'demo.tar.gz') -Algorithm SHA256
```

Upload pas in de vrijgegeven run deze twee pakketten, manifest en gerenderde configs naar een nieuwe privé stagingmap onder deploys home. Controleer hashes na transport. Geen lokale `.env`, preview-env.json, build-storage, source.tar of volledige werkmap uploaden. De volgende Linux-commando's worden uitgevoerd door de bevoegde beheerder, niet nu.

### 3. Accounts, releases en private configuratie

Voorstel roots is bevestigd vrij. Bij een herhaling alleen doorgaan als eigenaarschap/inhoud aantoonbaar van deze releaseprocedure is; stop bij onbekende bestaande users, groepen, units of mappen. Maak systeemaccounts zonder shell/home en zonder aanvullende groepen; nooit bestaande accounts aanpassen om een naamconflict te verbergen.

```bash
set -euo pipefail
umask 077
SITE=/var/www/kevin-webstudio
DEMO=/var/www/onderwijsdemo
read -r -p 'Volledige beoordeelde SHA: ' RELEASE
[[ "$RELEASE" =~ ^[a-f0-9]{40}$ ]] || exit 1
read -r -p 'Absolute privé stagingmap: ' STAGE
[[ "$STAGE" = /home/deploy/* && -d "$STAGE" ]] || exit 1
# Eerst bovenstaande bestaande namen/rechten beoordelen.
getent passwd kw-site >/dev/null || sudo useradd --system --user-group --no-create-home --shell /usr/sbin/nologin kw-site
getent passwd kw-demo >/dev/null || sudo useradd --system --user-group --no-create-home --shell /usr/sbin/nologin kw-demo
id kw-site; id kw-demo # stop als aanvullende groepen aanwezig zijn
sudo install -d -o root -g root -m 0711 "$SITE" "$SITE/releases" "$DEMO" "$DEMO/releases"
sudo install -d -o root -g kw-site -m 0710 "$SITE/shared"
sudo install -d -o kw-site -g kw-site -m 0700 "$SITE/shared/database" "$SITE/shared/backups" "$SITE/shared/storage"
for d in framework/cache/data framework/sessions framework/views logs app/private; do
  sudo install -d -o kw-site -g kw-site -m 0700 "$SITE/shared/storage/$d"
done
# Nieuwe immutable release directories; bij bestaande directories stoppen en beoordelen.
sudo test ! -e "$SITE/releases/$RELEASE"
sudo test ! -e "$DEMO/releases/$RELEASE"
sudo install -d -o root -g kw-site -m 0750 "$SITE/releases/$RELEASE"
sudo install -d -o root -g kw-demo -m 0750 "$DEMO/releases/$RELEASE"
sudo tar --no-same-owner -xzf "$STAGE/site.tar.gz" -C "$SITE/releases/$RELEASE"
sudo tar --no-same-owner -xzf "$STAGE/demo.tar.gz" -C "$DEMO/releases/$RELEASE"
sudo chown -R root:kw-site "$SITE/releases/$RELEASE"
sudo find "$SITE/releases/$RELEASE" -type d -exec chmod 0750 {} +
sudo find "$SITE/releases/$RELEASE" -type f -exec chmod 0640 {} +
sudo chmod 0711 "$SITE/releases/$RELEASE"
sudo find "$SITE/releases/$RELEASE/public" -type d -exec chmod 0755 {} +
sudo find "$SITE/releases/$RELEASE/public" -type f -exec chmod 0644 {} +
sudo chown -R root:kw-demo "$DEMO/releases/$RELEASE"
sudo find "$DEMO/releases/$RELEASE" -type d -exec chmod 0750 {} +
sudo find "$DEMO/releases/$RELEASE" -type f -exec chmod 0640 {} +
sudo chown kw-site:kw-site "$SITE/releases/$RELEASE/bootstrap/cache"
sudo chmod 0700 "$SITE/releases/$RELEASE/bootstrap/cache"
sudo ln -s "$SITE/shared/storage" "$SITE/releases/$RELEASE/storage"
sudo ln -s "$SITE/shared/.env" "$SITE/releases/$RELEASE/.env"
```

De demo-release bevat geen symlink naar hostdirectories, schrijfbare bestanden of gekopieerde .env. Controleer `find "$DEMO/releases/$RELEASE" -type l` (leeg). Een PHP-chroot is pas bewezen na controle via het daadwerkelijke poolproces: een tijdelijke lokale, afgeschermde diagnostische proef onder de gekozen FPM-config moet lezen/schrijven van host-site-.env, DB en andere sites weigeren. Gebruik geen echte geheimen als testinhoud en publiceer nooit phpinfo. Verwijder uitsluitend die eigen diagnostische proef vóór activering.

Maak bij de **eerste** installatie `shared/.env` privé aan (0600, kw-site eigenaar); vul via een beveiligde editor, niet via commandoregel/history, de namen uit `site.env.example` in. Gebruik de bestaande goedgekeurde TransIP-instellingen uit het mailrunbook; kopieer geen volledige ontwikkelomgeving. APP_ENV=production, APP_DEBUG=false, APP_URL HTTPS. Sessies database, secure host-only cookie, encrypt=true, cache database, queue database. Geen parent-domain-cookie die met demo gedeeld wordt. `MAIL_URL=null`, TLS-certificaatverificatie aan. Contactontvanger en beide afzendernamen behouden. Geen `DEMO_TODAY` of testtransport. Demo heeft **geen** .env. Nog geen zichtbaar demodoel: `EDUCATION_DEMO_ENABLED=false`.

Maak een **nieuwe productie-APP_KEY** éénmaal met `sudo -u kw-site /usr/bin/php8.5 artisan key:generate --force` vanuit de nieuwe site-release, vóór eerste data. Laat eerst bewust APP_KEY leeg in het nieuwe privébestand. Bij latere releases bestaande sleutel behouden; nooit opnieuw genereren. Bevestig vervolgens in privé dat geen `REPLACE_`-waarden of lege SMTP-credentials overblijven; toon de waarden niet in terminalrapporten. Sluit de editor vóór caches worden gemaakt.

### 4. Database, dependencies, caches en afzonderlijke runtimetest

Eerste keer: maak alleen als afwezig een leeg SQLite-bestand aan als kw-site, mode0600. Map0700 laat SQLite eigen journal/WAL/SHM met dezelfde private rechten maken. Geen lokale data, accounts of jobs importeren. Bestaande DB nooit trunceren.

```bash
set -euo pipefail
SITE=/var/www/kevin-webstudio
# RELEASE is de hierboven gecontroleerde SHA; voer in dezelfde shell uit.
cd "$SITE/releases/$RELEASE"
sudo -u kw-site /usr/bin/php8.5 /usr/bin/composer check-platform-reqs --no-dev
if ! sudo test -e "$SITE/shared/database/contact.sqlite"; then
  sudo install -o kw-site -g kw-site -m 0600 /dev/null "$SITE/shared/database/contact.sqlite"
fi
# Bij een bestaande database EERST snapshot maken, niet bij alleen dit lege bestand.
sudo -u kw-site /usr/bin/php8.5 artisan migrate --force --no-interaction
sudo -u kw-site /usr/bin/php8.5 artisan config:cache
sudo -u kw-site /usr/bin/php8.5 artisan route:cache
sudo -u kw-site /usr/bin/php8.5 artisan view:cache
```

Geen `composer setup`, migrate:fresh/reset, globale cache:clear, queue:clear of queue:retry all. `storage` is de private symlink, bootstrap-cache is per release. Test eerst exact deze PHP-serie en extensies in de genoemde **afzonderlijke fictieve omgeving**, niet met bovenstaande productie-.env of database. Mailfakes/arraytransport, tijdelijk DB/cache/sessies/queue, geen algemene scheduler. Na akkoord pas `current` symlinks atomair naar de beide nieuwe releases wijzen (`ln -s` naar een nieuw `.next`-pad, dan `mv -T` over de bekende current-symlink; stop bij een echte directory of bestaand onverwacht `.next`-pad). Noteer oude symlinkdoelen. Deze handeling is deployment en hoort uitsluitend bij de volgende run.

### 5. Webserver/FPM, DNS en HTTPS

Installeer gerenderde FPM-configs root:root0644 onder `/etc/kevin-webstudio`; services root:root0644 onder `/etc/systemd/system`. Neem **geen** gedeelde pool-/serviceconfig over en herstart geen andere workers. Controleer vóór starten:

```bash
set -euo pipefail
sudo /usr/sbin/php-fpm8.5 -t --fpm-config /etc/kevin-webstudio/fpm-site.conf
sudo /usr/sbin/php-fpm8.5 -t --fpm-config /etc/kevin-webstudio/fpm-demo.conf
sudo systemd-analyze verify /etc/systemd/system/kw-site-fpm.service /etc/systemd/system/kw-demo-fpm.service /etc/systemd/system/kw-contact-worker.service /etc/systemd/system/kw-scheduler.service /etc/systemd/system/kw-scheduler.timer
# Alleen na geslaagde checks in deze vrijgegeven run:
sudo systemctl daemon-reload
sudo systemctl enable --now kw-site-fpm.service kw-demo-fpm.service
```

Main FPM/user moet private code/config kunnen lezen en uitsluitend storage/database/cache schrijven; demo-user niet. Socket0600 voor www-data, geen algemeen schrijfbare socket. Nginx-user op server opnieuw bevestigen. Bind alleen de bestaande publieke Nginx-poorten; FPM gebruikt Unix-sockets.

DNS-volgorde (alleen webrecords, eerst huidige zone exporteren/screenshot privé bewaren):

1. Beoordeel/lage TTL voor de bestaande web-A/AAAA/CNAME, wacht oude TTL af. Laat **MX, SPF/TXT, DKIM-selectors en DMARC exact intact**, geen nameserverwijziging.
2. Richt ACME-webroot `/var/lib/kevin-webstudio-acme/.well-known/acme-challenge` in (publiek leesbaar, alleen beheerder schrijfbaar). Installeer alleen `kw-http-bootstrap`, maak gerichte sites-enabled-symlink, `nginx -t` en alleen dan reload Nginx. Hercontroleer alle bestaande sites. Deze host geeft 503 behalve ACME; nog geen formulier of mail.
3. Zet **A apex `@` → `146.59.231.208`**, behoud **www CNAME → `kevinwebstudio.nl.`**, zet expliciet **A `onderwijsdemo` → `146.59.231.208`**. Controleer of demo nu via wildcard erft en maak zo nodig een expliciet record zonder de wildcard voor andere namen te wijzigen.
4. De templates luisteren voorlopig alleen IPv4. Verwijder daarom het oude apex-AAAA **`2a01:7c8:e100:1::50a0`** en eventuele demo-AAAA/ongewenste AAAA-erfenis voor deze hostnamen. www erft via CNAME. DNS-provider moet per host wildcard-AAAA kunnen uitsluiten; verifieer resolutie. **Nog geen nieuw AAAA**. Pas later, na IPv6-listeners/firewall/per-host HTTPS-tests, optioneel `2001:41d0:305:2100::69c2` publiceren. Een werkende serverinterface alleen volstaat niet.
5. Controleer A/AAAA/CNAME via authoritative DNS én meerdere publieke resolvers; wacht propagatie/oude TTL af. Test bereikbaarheid van de ACME-webroot op alle drie hosts vóór certificaataanvraag.
6. Met de eerder vastgestelde Certbot-installatie (volgende run): `sudo certbot certonly --webroot -w /var/lib/kevin-webstudio-acme --cert-name kevinwebstudio.nl -d kevinwebstudio.nl -d www.kevinwebstudio.nl` en afzonderlijk `--cert-name onderwijsdemo.kevinwebstudio.nl -d onderwijsdemo.kevinwebstudio.nl`. Laat contact/TOS door Kevin afhandelen, geen verzonnen contactadres. Bij bestaande certificaten eerst hun namen/SAN's onderzoeken, niet opnieuw aanvragen onder willekeurige namen.
7. Controleer werkelijke certificaatpaden en render zonodig opnieuw. Installeer `kw-demo-fastcgi.conf` in snippets en de definitieve `kw-sites` naast de bootstrapconfig. Verwijder uitsluitend de eigen bootstrap-symlink uit sites-enabled, activeer eigen `kw-sites`, `nginx -t`; bij fout eigen symlinks herstellen en niet reloaden. Bij succes reload Nginx, controleer apex, www→apex en demo + alle bestaande hosts. Geen globale TLS-instellingen veranderen, geen HSTS-preload instellen.
8. Neem de certificaten op in bestaande vernieuwing, met een beoordeelde deploy-hook `nginx -t` gevolgd door reload. Test renewal later bewust (`certbot renew --dry-run` heeft netwerk/ACME-effecten), inspecteer dubbel mechanisme eerst; nu niet uitgevoerd.

### 6. Contactmail, één scheduler en vrijgeven bezoekersroute

Verhouding: SMTP-timeout **10s** in de bestaande contacttransportcode, worker **60s**, queue `retry_after=90`, stale claim **300s**, systemd nette stop **75s**. Worker draait alleen `database --queue=contact-mail --sleep=3 --tries=1 --timeout=60`. De duurzame intentie regelt maximaal drie pogingen met 60/300s wachten; geen extra systemd/Laravel-jobretrybeleid toevoegen. Linux PCNTL moet aanwezig zijn. SIGTERM laat de lopende job afronden; bij deploy eerst scheduler timer stoppen en een lopende scheduler laten afronden, worker netjes stoppen, daarna code/migratie/cache en alleen deze worker starten. Geen hard kill of queuelegen om te versnellen.

`routes/console.php` bevat naast contactdispatch ook dagelijkse verwijdering van verlopen teamuitnodigingen. Die bestaande taak blijft actief met `schedule:run`; er is **geen automatische contactprune**. Voorkom een tweede cronregel/`schedule:work`/timer voor deze site. `kw-scheduler.timer` heeft `Persistent=false`: geen inhaal-dispatch na downtime. Ook zonder worker kan `schedule:run` echte taken klaarzetten; start pas na bewuste vrijgave van verzending.

Na controle productieconfig/ontvangers, echte mailproef afgesproken en webchecks geslaagd:

```bash
set -euo pipefail
sudo systemctl enable --now kw-contact-worker.service
sudo systemctl enable --now kw-scheduler.timer
systemctl is-active kw-contact-worker.service kw-scheduler.timer
systemctl list-timers kw-scheduler.timer --no-pager
# Dagelijks door aangewezen beheerder (ook bij lege mailbox):
sudo -u kw-site /usr/bin/php8.5 /var/www/kevin-webstudio/current/deploy/mail-health.php /var/www/kevin-webstudio/shared/database/contact.sqlite
# Exit 2: failed/uncertain/achterstallige taken onderzoeken volgens contactrunbook.
# Exit 1: monitoring zelf faalt; ook opvolgen. Geen automatische retry of verzending.
```

De countcontrole kijkt naar **alle** relevante rijen, niet alleen de laatste twintig. Bewaar alleen datum/aantallen/actie in operationeel verslag. De beheerder voert dit dagelijks uit; automatische alarmering kan later bewust op dit read-only script worden aangesloten. Geen nieuwe mail-monitoringqueue. `uncertain` eerst bij provider/ontvanger verifiëren, anders risico dubbele verzending. Voor herstel gebruik het bestaande contactrunbook; toon geen `--show`/payloads in logs.

Bezoekersroute: **Werk → Onderwijsformulier-case → Probeer de onderwijsdemo → Aanvraagformulier → Demodashboard → Terug naar Kevin Webstudio**. De nieuwe knop staat direct onder de bestaande case-inleiding. Centrale URL in `config/demos.php`, aan/uit via `EDUCATION_DEMO_ENABLED` (standaard false). Het is een gewone anchor, geen cross-origin Inertia-request en geen nieuw tabblad. De demo heeft onderaan een teruglink naar de case, gebouwd met `VITE_WEBSTUDIO_LINK_ENABLED=true` uitsluitend in de releasebuild. Default ontwikkelbuild toont die niet. Formulier↔dashboard zijn hashlinks in hetzelfde document, zodat de eigen aanvraag blijft bestaan; teruggaan naar de site verlaat de tijdelijke demonstratieomgeving.

Activeer de hoofdsiteknop **pas na werkende demo-HTTPS/API en toestemming** door de privéflag op true te zetten, `config:cache` opnieuw op te bouwen en alleen `kw-site-fpm` en de betreffende contactworker netjes te herstarten indien ze draaien. De formulier-/dashboardlogica, goedgekeurde case-tekst en vormgeving zijn verder behouden. Geen publieke dode link in de huidige standaardconfiguratie.

### 7. Functionele vrijgave en herstel

- Controleer apex HTTPS, HTTP→HTTPS, www→apex met pad/query, demo HTTPS en beide hash-ingangen. Verifieer certificaatketen/SAN, cookies Secure/HttpOnly/SameSite en host-only domein; geen sessiecookies op demo. Bevestig geen externe bezoekerrequests of onverwachte storage.
- GET én POST `/register` moeten serverzijdig 404 zijn; afgeschermde appfuncties vragen authenticatie. Test `/.env`, `/.git/config`, `/database/contact.sqlite`, `/storage/`, `/vendor/`, willekeurig `.php` en gecodeerde traversal met `curl --path-as-is`; geen private inhoud. Demo `server/router.php`, bronbestanden, manifests, oude `/api/` en onverwachte endpoints 404. API verkeerde methode/query geweigerd, oversized body413, multipart niet bruikbaar, geen debugtraces. GET-seed100 en echte kalenderdag, statussen/validatie ongewijzigd.
- Geplande echte formulierproef met Kevins eigen gecontroleerde afzender: aanvraag duurzaam opgeslagen, beide mailintenties onafhankelijk verwerkt. Controleer **ontvangst van beide mails** (ook spam), afzenders, melding Reply-To naar testaanvrager, bevestiging Reply-To naar info@kevinwebstudio.nl, en ontvangende `Authentication-Results` voor SPF/DKIM/DMARC plus alignment. Status `accepted` bewijst uitsluitend SMTP-acceptatie. `studio:mail-test --send` test slechts de melding en vervangt deze dubbele formulierproef niet.
- Demo in twee tabbladen: nieuwe aanvraag alleen in A; afronden→precies dezelfde detailpagina; bewerken/bevestigen; tab B onveranderd; refresh/reset terug100, eigen aanvraag verdwenen; geen browser- of serveropslag/mail. Controleer 390/1440px en teruglink. Herhaal niet onnodig de gehele historische vormgevingsaudit.
- Controleer alle vooraf vastgelegde bestaande VPS-sites en hun certificaten. Test service-herstart van **alleen** nieuwe services, timer éénmaal per minuut en dagelijkse mailcontrole. Leg feitelijke checks en resterende afwijkingen vast; pas daarna “live” noemen.

Latere consistente databaseback-up (zelfde release-PHP, private nieuwe bestemming, geen inhoud tonen):

```bash
set -euo pipefail
SITE=/var/www/kevin-webstudio
STAMP=$(date -u +%Y%m%dT%H%M%SZ)
sudo -u kw-site /usr/bin/php8.5 "$SITE/current/deploy/backup-sqlite.php" "$SITE/shared/database/contact.sqlite" "$SITE/shared/backups/contact-$STAMP.sqlite"
```

Back-up-API maakt een consistente snapshot inclusief actieve WAL-toestand; script checkt `quick_check`, weigert bestaande bestemming en toont geen inhoud. Controleer later een herstel in een **afgesloten fictieve/gesaneerde testomgeving zonder mail**, encryptie/off-sitekopie en herstelduur. Een mislukte bestemming blijft expliciet ongeldig, niet automatisch verwijderen of gebruiken.

Code-rollback: stop alleen deze scheduler/worker netjes, zet beide bekende `current`-symlinks terug, bouw caches voor de vorige code, herstart alleen beide nieuwe FPM-services/contactworker en hervat timer na checks. Omdat demo chroot op de release wijst is **herstart van demo-FPM verplicht** bij een symlinkwissel. De gedeelde PHP-service en andere sites blijven draaien. Database blijft behouden; rollback vereist dat schema en oude code compatibel zijn. Geen automatische `migrate:rollback`.

Databaseherstel is een apart besluit: blokkeer eerst nieuwe inzendingen, stop eigen timer/worker netjes, bewaar een consistente snapshot van de huidige DB inclusief **alle sinds de back-up ontvangen aanvragen**. Onderzoek en reconcilieer die inzendingen/mailstatussen gericht vóór terugzetten of opnieuw versturen; een oude DB kan reeds afgeleverde mail weer pending maken. Nooit blind over een actieve database kopiëren, nooit nieuwe aanvragen stilzwijgend overschrijven. Zet een bewezen back-up pas na akkoord met dezelfde private eigenaar/rechten terug terwijl alle schrijvers uitstaan, behandel WAL/SHM onder SQLite-beheer, pas latere verwijderverzoeken opnieuw toe en controleer integriteit vóór heropenen. DNS/configrollback alleen naar de vastgelegde oorspronkelijke webwaarden; mailrecords blijven intact.

## Controles van deze voorbereidingsrun

De concrete resultaten staan in [publicatievoorbereiding-controle](../demos/publicatievoorbereiding-controle.md). Tijdelijke helpers, runtimes, testdatabases, screenshots en releasebuilds staan buiten het project. Geen inhoudelijke privacywijzigingen of volledige regressie-/vormgevingsaudit voor config/documentatie. Syntaxis-/contracttests zijn geen actieve VPS-isolatietest.

Technische onderbouwing: [Nginx FastCGI](https://nginx.org/en/docs/http/ngx_http_fastcgi_module.html) voor vaste scriptparameters, [PHP FPM](https://www.php.net/manual/en/install.fpm.configuration.php) voor poolgebruikers/chroot/clear_env, [SQLite backup-API](https://www.sqlite.org/backup.html) voor consistente snapshots. Runtime 8.4.15 komt uit het [officiële PHP Windows-archief](https://downloads.php.net/~windows/releases/archives/). Applicatiespecifieke claims zijn gebaseerd op de vastgelegde code, niet op generieke hostingadviezen.

## Nog vijf gebundelde besluiten voor Kevin

1. **Identiteit en contact:** welke juridische naam/rechtsvorm, vestigingsland en zakelijke contactgegevens moeten definitief gepubliceerd worden? Wie bewaakt info@ voor privacyverzoeken, controleert proportioneel identiteit en bewaakt de reactie binnen één maand? Bevestig ook de concrete belangenafweging uit het privacydossier; geen algemene juridische garantie.
2. **Leveranciers en locaties:** bevestig VPS-/hostingpartij, TransIP-afspraken, opslag/toegang/subverwerkers, verwerkersovereenkomsten en eventuele internationale doorgiften/waarborgen, inclusief mailboxclients, forwarding, logs en back-ups. Nu niet vastgesteld.
3. **Bewaren en beheer:** akkoord of aanpassing op het bestaande voorstel (90 dagen afgeronde websitekopie, 120 min sessies met fysieke opruimronde, 14 dagen logs, 30 dagen back-ups, 6 maanden correspondentie zonder opdracht)? Wie doet dagelijkse mailcontrole, periodieke verwijdering, uitzonderingen/legal holds, versleutelde off-siteback-ups en herstel-/wisverzoeken? Alleen retentie instellen bouwt nog geen ontbrekende opruimtaak.
4. **Publicatierechten:** is er toestemming voor publicatie van de geselecteerde broncode/regellogica en herkenbare namen uit de beide demomanifesten? Leg eventuele naamsaanpassing/beperking vóór publicatie vast; geen aannames uit fictieve scholen afleiden.
5. **Uitvoering en vrijgave:** akkoord op demo-subdomein, IPv4-eerst, eigen accounts/FPM-chroot en latere PR naar main? Wie levert interactieve server-/DNS-beheerstoegang, voert de Linux/FPM-isolatieproef en echte dubbele mail-/headercontrole uit en geeft daarna expliciet de publicatierun vrij? Certificaatbeheer en ontbrekende root-only feiten eerst bevestigen.

**Eerste handeling volgende run:** besluiten/vrijgave vastleggen, branch/remote en ontbrekende serverfeiten opnieuw alleen-lezen controleren, daarna een volledige beoordeelde release-SHA kiezen en de lokale artefacten met `deploy/prepare.mjs` bouwen. Geen live actie uitsluitend op basis van deze voorbereidingscommit.
