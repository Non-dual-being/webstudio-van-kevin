[Unit]
Description=Kevin Webstudio contact-mail worker only
After=network-online.target
Wants=network-online.target
[Service]
Type=simple
User=kw-site
Group=kw-site
WorkingDirectory=@@SITE_ROOT@@/current
ExecStart=/usr/bin/php@@PHP_SERIES@@ artisan queue:work database --queue=contact-mail --sleep=3 --tries=1 --timeout=60
KillSignal=SIGTERM
KillMode=mixed
TimeoutStopSec=75
Restart=always
RestartSec=5
UMask=0077
NoNewPrivileges=true
ProtectSystem=strict
ProtectHome=true
PrivateTmp=true
ReadWritePaths=@@SITE_ROOT@@/shared
StandardOutput=null
StandardError=null
[Install]
WantedBy=multi-user.target
