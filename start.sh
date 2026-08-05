#!/bin/bash

# Render Container Runtime Initialization & Startup Script
set -e

echo "===> Starting Laravel Runtime Initialization..."

# Dynamic Port Binding for Render ($PORT or default 8080)
TARGET_PORT="${PORT:-8080}"
echo "===> Binding Nginx to Port ${TARGET_PORT}..."
sed -i "s/listen [0-9]*/listen ${TARGET_PORT}/g" /etc/nginx/http.d/default.conf

# Ensure required storage directories exist
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Generate storage link if missing
if [ ! -L /var/www/html/public/storage ]; then
    echo "===> Creating Public Storage Link..."
    php artisan storage:link || true
fi

# Clear and optimize application cache
echo "===> Optimizing Laravel Caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run Database Migrations in production automatically
echo "===> Running Database Migrations..."
php artisan migrate --force || echo "Database migration warning: failed or skipped"

echo "===> Launching Process Supervisor (Nginx + PHP-FPM + Worker)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
