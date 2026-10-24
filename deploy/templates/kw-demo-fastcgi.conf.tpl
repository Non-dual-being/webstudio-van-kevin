# Deliberately no generic fastcgi.conf (which can set SCRIPT_FILENAME again).
# The original URI, method, content type/length and query reach server/router.php.
fastcgi_param SCRIPT_FILENAME /server/router.php;
fastcgi_param SCRIPT_NAME /server/router.php;
fastcgi_param DOCUMENT_ROOT /dist;
fastcgi_param REQUEST_URI $request_uri;
fastcgi_param QUERY_STRING $query_string;
fastcgi_param REQUEST_METHOD $request_method;
fastcgi_param CONTENT_TYPE $content_type;
fastcgi_param CONTENT_LENGTH $content_length;
fastcgi_param SERVER_PROTOCOL $server_protocol;
fastcgi_param GATEWAY_INTERFACE CGI/1.1;
fastcgi_param SERVER_SOFTWARE nginx;
fastcgi_param SERVER_NAME $server_name;
fastcgi_param SERVER_PORT $server_port;
fastcgi_param HTTPS on;
fastcgi_param REDIRECT_STATUS 200;
fastcgi_pass_request_headers off;
fastcgi_pass unix:/run/kw-demo/php.sock;
fastcgi_connect_timeout 3s;
fastcgi_read_timeout 20s;
fastcgi_send_timeout 10s;
fastcgi_hide_header X-Powered-By;
fastcgi_hide_header Set-Cookie;
