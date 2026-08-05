#!/bin/bash
# Laravel Container Runtime Initialization Script for Render

echo "===> Starting Laravel Runtime Initialization..."

# 1. Ensure all storage and framework directories exist
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# 2. Set full read/write permissions for Nginx/PHP-FPM user
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache || true

# 3. Create storage symlink
php /var/www/html/artisan storage:link || true

# 4. Clear stale build-time caches so runtime env vars (APP_KEY, DATABASE_URL) load properly
php /var/www/html/artisan config:clear || true
php /var/www/html/artisan route:clear || true
php /var/www/html/artisan view:clear || true
php /var/www/html/artisan cache:clear || true

# 5. Run Database Migrations in production
echo "===> Running Database Migrations..."
php /var/www/html/artisan migrate --force || echo "Migration skipped or database not ready"

# 6. Cache routes and config dynamically with real runtime environment variables
php /var/www/html/artisan config:cache || true
php /var/www/html/artisan route:cache || true

echo "===> Initialization Completed!"
