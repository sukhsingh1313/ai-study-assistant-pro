#!/bin/bash
# Automatic database migration script for Render deployment
set -e

echo "===> Running Database Migrations on Render..."
php /var/www/html/artisan migrate --force || echo "Migration warning: check database connection"
