/var/log/nginx/kw-site.access.log /var/log/nginx/kw-demo.access.log {
    daily
    rotate 14
    missingok
    notifempty
    compress
    delaycompress
    create 0640 www-data adm
    sharedscripts
    postrotate
        /usr/sbin/nginx -s reopen
    endscript
}
# Alternative stanza only if /etc/logrotate.d/nginx does NOT already cover these.
# Never install overlapping rotation rules; see runbook.
