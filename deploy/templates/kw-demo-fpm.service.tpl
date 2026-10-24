[Unit]
Description=Stateless education demo dedicated PHP FPM
After=network.target
[Service]
Type=simple
RuntimeDirectory=kw-demo
RuntimeDirectoryMode=0755
ExecStart=/usr/sbin/php-fpm@@PHP_SERIES@@ --nodaemonize --fpm-config /etc/kevin-webstudio/fpm-demo.conf
KillSignal=SIGQUIT
TimeoutStopSec=25
Restart=on-failure
RestartSec=5
UMask=0077
ProtectSystem=strict
ProtectHome=true
PrivateTmp=true
PrivateDevices=true
PrivateNetwork=true
RestrictAddressFamilies=AF_UNIX
ReadWritePaths=/run/kw-demo
InaccessiblePaths=@@SITE_ROOT@@
NoNewPrivileges=true
StandardOutput=null
StandardError=null
[Install]
WantedBy=multi-user.target
