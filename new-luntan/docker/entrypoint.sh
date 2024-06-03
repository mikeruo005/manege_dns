#!/usr/bin/env sh
set -e

php-fpm -D

crond

php artisan set-data:init clear

supervisord -c '/etc/supervisord.conf'

nginx -g 'daemon off;'

chmod -R 777 /app/storage/logs/

ls

