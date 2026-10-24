[Unit]
Description=Kevin Webstudio dedicated PHP FPM
After=network.target
[Service]
Type=simple
RuntimeDirectory=kw-site
RuntimeDirectoryMode=0755
ExecStart=/usr/sbin/php-fpm@@PHP_SERIES@@ --nodaemonize --fpm-config /etc/kevin-webstudio/fpm-site.conf
KillSignal=SIGQUIT
TimeoutStopSec=75
Restart=on-failure
RestartSec=5
UMask=0077
PrivateTmp=true
ProtectSystem=strict
ProtectHome=true
ReadWritePaths=@@SITE_ROOT@@/shared @@SITE_ROOT@@/current/bootstrap/cache /run/kw-site
NoNewPrivileges=true
StandardOutput=null
StandardError=null
[Install]
WantedBy=multi-user.target
