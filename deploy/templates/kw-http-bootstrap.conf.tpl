# Install ONLY during the later authorized release. No TLS dependency yet.
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
    location / { return 503; }
}
