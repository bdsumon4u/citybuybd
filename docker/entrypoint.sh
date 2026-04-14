#!/usr/bin/env sh
set -e

cd /var/www/html

mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
mkdir -p backend
mkdir -p frontend
chown -R www-data:www-data storage bootstrap/cache backend frontend

if [ ! -L public/storage ]; then
    php artisan storage:link || true
fi

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force
fi

exec "$@"
