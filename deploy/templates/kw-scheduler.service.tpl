[Unit]
Description=Kevin Webstudio scheduler (can dispatch contact mail)
[Service]
Type=oneshot
User=kw-site
Group=kw-site
WorkingDirectory=@@SITE_ROOT@@/current
ExecStart=/usr/bin/php@@PHP_SERIES@@ artisan schedule:run --no-interaction
TimeoutStartSec=50
UMask=0077
NoNewPrivileges=true
ProtectSystem=strict
ProtectHome=true
PrivateTmp=true
ReadWritePaths=@@SITE_ROOT@@/shared
StandardOutput=null
StandardError=null
