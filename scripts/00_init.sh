#!/bin/bash
# Container Runtime Startup Script for Render

echo "===> Initializing Storage Directories..."
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache || true

echo "===> Creating Storage Link..."
php /var/www/html/artisan storage:link || true

echo "===> Clearing Stale Caches..."
php /var/www/html/artisan config:clear || true
php /var/www/html/artisan route:clear || true
php /var/www/html/artisan view:clear || true
php /var/www/html/artisan cache:clear || true

echo "===> Running Database Migrations..."
php /var/www/html/artisan migrate --force || echo "Migrations pending database connection"

echo "===> Caching Production Routes..."
php /var/www/html/artisan route:cache || true

echo "===> Container Runtime Setup Complete!"
