#!/bin/sh
set -e

# ---------------------------------------------------------------
# entrypoint.sh
# Dijalankan setiap container start. Caching config/route/view
# dilakukan di sini (bukan saat build) supaya environment variable
# dari Railway (APP_KEY, DB_*, dll) sudah tersedia.
# ---------------------------------------------------------------

# Generate APP_KEY otomatis kalau belum di-set di Railway Variables
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY kosong, generating..."
    php artisan key:generate --force
fi

echo "Clearing old cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Caching config, route, and view..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting Laravel server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"