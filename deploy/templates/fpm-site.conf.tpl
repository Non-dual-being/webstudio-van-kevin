[global]
pid = /run/kw-site/php.pid
error_log = /dev/null
daemonize = no
[kw-site]
user = kw-site
group = kw-site
listen = /run/kw-site/php.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0600
pm = ondemand
pm.max_children = 5
pm.process_idle_timeout = 10s
pm.max_requests = 300
clear_env = yes
chdir = @@SITE_ROOT@@/current
security.limit_extensions = .php
request_terminate_timeout = 60s
catch_workers_output = no
php_admin_flag[display_errors] = off
php_admin_flag[log_errors] = off
php_admin_flag[expose_php] = off
php_admin_flag[cgi.fix_pathinfo] = off
php_admin_value[memory_limit] = 256M
php_admin_value[post_max_size] = 64K
php_admin_flag[file_uploads] = off
php_admin_value[date.timezone] = Europe/Amsterdam
