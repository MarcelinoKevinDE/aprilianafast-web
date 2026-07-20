#!/bin/sh
set -e

# ---------------------------------------------------------------
# entrypoint.sh
# Dijalankan setiap container start. Caching config/route/view
# dilakukan di sini (bukan saat build) supaya environment variable
# dari Railway (APP_KEY, DB_*, dll) sudah tersedia.
#
# CATATAN: File .env TIDAK ada di dalam image (lihat .dockerignore) —
# ini disengaja demi keamanan. Semua config HARUS di-set lewat
# environment variable di Railway dashboard (tab "Variables"),
# bukan lewat file .env di dalam container.
# ---------------------------------------------------------------

if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY belum di-set!"
    echo "Generate dulu di lokal dengan: php artisan key:generate --show"
    echo "Lalu paste hasilnya (base64:xxxx...) ke Railway > Variables > APP_KEY"
    exit 1
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