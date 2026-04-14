#!/usr/bin/env sh
set -e

cd /var/www/html

umask 0002

mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
mkdir -p backend
mkdir -p frontend

# Keep mounted volumes writable for php-fpm (www-data) and admin scripts.
chown -R www-data:www-data storage bootstrap/cache backend frontend || true
chmod -R ug+rwX storage bootstrap/cache backend frontend || true

# Ensure Laravel log file exists and is writable.
touch storage/logs/laravel.log || true
chown www-data:www-data storage/logs/laravel.log || true
chmod 664 storage/logs/laravel.log || true

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
