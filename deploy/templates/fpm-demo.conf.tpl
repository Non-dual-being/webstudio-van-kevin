[global]
pid = /run/kw-demo/php.pid
error_log = /dev/null
daemonize = no
[kw-demo]
user = kw-demo
group = kw-demo
listen = /run/kw-demo/php.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0600
pm = ondemand
pm.max_children = 3
pm.process_idle_timeout = 10s
pm.max_requests = 200
clear_env = yes
# Master binds socket before workers enter the read-only minimal release filesystem.
# No host /tmp, /etc, site release, .env, DB, SMTP config or other sites inside it.
chroot = @@DEMO_ROOT@@/current
chdir = /
security.limit_extensions = .php
request_terminate_timeout = 15s
catch_workers_output = no
php_admin_flag[display_errors] = off
php_admin_flag[log_errors] = off
php_admin_flag[expose_php] = off
php_admin_flag[cgi.fix_pathinfo] = off
php_admin_flag[file_uploads] = off
php_admin_flag[session.auto_start] = off
php_admin_flag[allow_url_fopen] = off
php_admin_flag[allow_url_include] = off
php_admin_flag[ffi.enable] = off
php_admin_value[open_basedir] = /
php_admin_value[disable_functions] = mail,exec,passthru,shell_exec,system,proc_open,popen,pcntl_exec,putenv,dl
php_admin_value[memory_limit] = 128M
php_admin_value[max_execution_time] = 10
php_admin_value[post_max_size] = 32K
php_admin_value[date.timezone] = Europe/Amsterdam
# Intentionally NO env[DEMO_TODAY], session/database/mail configuration.
