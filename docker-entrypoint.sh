#!/bin/sh
set -e

cd /var/www/html

php artisan migrate --force

php artisan app:create-admin-user

chown -R www-data:www-data storage bootstrap/cache || true

exec "$@"