#!/bin/bash

# Universal Deployment Script for Hostinger / VPS / Local Environments
set -e

echo "🚀 Starting Deployment Process for AI Study Assistant..."

# 1. Enable Maintenance Mode
php artisan down || true

# 2. Pull Latest Changes (if Git repository)
if [ -d ".git" ]; then
    echo "📦 Pulling latest changes from Git..."
    git pull origin main || git pull origin master
fi

# 3. Install/Update PHP Dependencies
echo "🐘 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# 4. Storage Link Setup
echo "🔗 Ensuring Storage Symlink..."
php artisan storage:link || true

# 5. Database Migrations
echo "🗄️ Running Database Migrations..."
php artisan migrate --force

# 6. Optimize Caches
echo "⚡ Caching Configs, Routes, and Views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache 2>/dev/null || true

# 7. Restart Queue Workers
echo "🔄 Restarting Queue Workers..."
php artisan queue:restart || true

# 8. Disable Maintenance Mode
php artisan up

echo "✅ Deployment Completed Successfully!"
