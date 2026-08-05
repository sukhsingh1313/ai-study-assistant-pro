#!/bin/bash

# Render & Cloud Container Build Script
set -e

echo "===> Starting Laravel Build Process..."

# Install production dependencies
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Create storage symlink
php artisan storage:link || true

echo "===> Build Completed Successfully!"
