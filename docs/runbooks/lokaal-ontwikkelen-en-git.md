# Kevin Webstudio lokaal ontwikkelen

Projectmap: `C:\wamp64\www\kevin-webstudio`

## Website starten

Open twee PowerShell-vensters.

### Venster 1 — Laravel

```powershell
Set-Location -LiteralPath 'C:\wamp64\www\kevin-webstudio'
$env:Path = 'C:\wamp64\bin\php\php8.4.15;' + $env:Path
php artisan serve --host=127.0.0.1 --port=8000
```

### Venster 2 — Vite

```powershell
Set-Location -LiteralPath 'C:\wamp64\www\kevin-webstudio'
pnpm dev
```

Open daarna <http://127.0.0.1:8000>. Stop een server met `Ctrl+C` in het bijbehorende venster.

## Voor je iets wijzigt

```powershell
Set-Location -LiteralPath 'C:\wamp64\www\kevin-webstudio'
git status --short
git branch --show-current
git log -1 --oneline
```

De huidige basis hoort op `feature/public-foundation` bij commit `2991003` te staan. Ga niet pullen, wisselen of verwijderen wanneer `git status --short` onverwachte regels toont.

## Wijzigingen controleren en committen

```powershell
Set-Location -LiteralPath 'C:\wamp64\www\kevin-webstudio'
$env:Path = 'C:\wamp64\bin\php\php8.4.15;' + $env:Path

composer validate
& '.\vendor\bin\pint.bat' --test
& '.\vendor\bin\phpstan.bat' analyse --memory-limit=512M
php artisan test
pnpm lint:check
pnpm format:check
pnpm types:check
pnpm build
git diff --check

git status --short
git diff
git add --patch
# Voeg nieuwe bestanden daarna bewust per pad toe, bijvoorbeeld:
# git add 'docs\runbooks\lokaal-ontwikkelen-en-git.md'
git diff --cached --check
git diff --cached
git commit -m 'feat: beschrijf de wijziging kort'

$Branch = (git branch --show-current).Trim()
git push -u origin $Branch
```

Commit of push niet als een controle faalt. Voeg `.env`, databases, `vendor`, `node_modules` en `public/build` nooit toe.

## Nieuwe functie starten

Maak pas na review en samenvoegen van de publieke fundering een nieuwe branch vanaf bijgewerkte `main`:

```powershell
git switch main
git pull --ff-only origin main
git switch -c feature/onderwijsformulier-demo
```

Controleer steeds met `git status --short` wat Git gaat meenemen.
