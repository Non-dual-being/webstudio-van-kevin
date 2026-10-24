# /etc/nginx/sites-available/kw-sites; replaces ONLY kw-http-bootstrap.
# log_format belongs in http context (sites-enabled is included there on Ubuntu).
# No IP, URL/query, referrer, user-agent, cookie or request body in access logs.
log_format kw_min '$time_iso8601 $server_name $request_method $status $body_bytes_sent $request_time';
server {
    listen 80;
    server_name kevinwebstudio.nl www.kevinwebstudio.nl onderwijsdemo.kevinwebstudio.nl;
    access_log off;
    error_log /dev/null crit;
    location ^~ /.well-known/acme-challenge/ {
        root /var/lib/kevin-webstudio-acme;
        default_type text/plain;
        try_files $uri =404;
    }
    location / { return 308 https://$host$request_uri; }
}
server {
    listen 443 ssl;
    server_name www.kevinwebstudio.nl;
    ssl_certificate /etc/letsencrypt/live/@@SITE_CERT@@/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/@@SITE_CERT@@/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    access_log off;
    error_log /dev/null crit;
    return 308 https://kevinwebstudio.nl$request_uri;
}
server {
    listen 443 ssl;
    server_name kevinwebstudio.nl;
    root @@SITE_ROOT@@/current/public;
    index index.php;
    ssl_certificate /etc/letsencrypt/live/@@SITE_CERT@@/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/@@SITE_CERT@@/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    access_log /var/log/nginx/kw-site.access.log kw_min;
    error_log /dev/null crit;
    client_max_body_size 64k;
    client_body_timeout 10s;
    send_timeout 20s;
    add_header X-Content-Type-Options nosniff always;
    add_header Referrer-Policy strict-origin-when-cross-origin always;
    location ~ /\. { return 404; }
    location ~* ^/(?:vendor|storage|bootstrap|config|database|resources|routes|tests|deploy|docs|demos)(?:/|$) { return 404; }
    location ~* \.(?:env|sqlite(?:3)?|db|log|sql|bak|zip|tar|gz)$ { return 404; }
    location / { try_files $uri $uri/ /index.php?$query_string; }
    location = /index.php {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root/index.php;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        fastcgi_param HTTPS on;
        fastcgi_param HTTP_PROXY "";
        fastcgi_pass unix:/run/kw-site/php.sock;
        fastcgi_connect_timeout 5s;
        fastcgi_read_timeout 65s;
        fastcgi_hide_header X-Powered-By;
    }
    location ~ \.php(?:/|$) { return 404; }
}
server {
    listen 443 ssl;
    server_name onderwijsdemo.kevinwebstudio.nl;
    root @@DEMO_ROOT@@/current/dist;
    ssl_certificate /etc/letsencrypt/live/@@DEMO_CERT@@/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/@@DEMO_CERT@@/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    access_log /var/log/nginx/kw-demo.access.log kw_min;
    error_log /dev/null crit;
    client_max_body_size 32k;
    client_body_buffer_size 32k;
    client_body_timeout 10s;
    send_timeout 20s;
    add_header X-Content-Type-Options nosniff always;
    add_header X-Robots-Tag 'noindex, nofollow' always;
    # All allowed paths use the fixed router, preserving its headers and validation.
    # SCRIPT_FILENAME is INSIDE the dedicated FPM chroot, not a client-controlled path.
    location = / { include /etc/nginx/snippets/kw-demo-fastcgi.conf; }
    location ~ ^/assets/[A-Za-z0-9_-]+\.(js|css|woff2?)$ {
        include /etc/nginx/snippets/kw-demo-fastcgi.conf;
    }
    location ~ ^/demo-api/(rules|policy|programs|seed|dates|roster|quote|complete|change)$ {
        include /etc/nginx/snippets/kw-demo-fastcgi.conf;
    }
    location / { return 404; }
}
