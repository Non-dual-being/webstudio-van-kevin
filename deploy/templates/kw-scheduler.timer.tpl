[Unit]
Description=Kevin Webstudio schedule once per minute
[Timer]
OnCalendar=*-*-* *:*:00
AccuracySec=1s
Persistent=false
Unit=kw-scheduler.service
[Install]
WantedBy=timers.target
