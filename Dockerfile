FROM richarvey/nginx-php-fpm:3.1.6

# Set working directory
WORKDIR /var/www/html

# Environment variables for richarvey/nginx-php-fpm container configuration
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV ERRORS=0
ENV RUN_SCRIPTS=1

# Copy application code
COPY . /var/www/html

# Install Composer production dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Create storage symlink
RUN php artisan storage:link || true

# Run optimization and cache clearing / warming
RUN php artisan config:clear || true \
    && php artisan cache:clear || true \
    && php artisan route:clear || true \
    && php artisan view:clear || true \
    && php artisan config:cache || true \
    && php artisan route:cache || true \
    && php artisan view:cache || true \
    && php artisan optimize || true

# Set directory permissions for Laravel storage and bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose HTTP port
EXPOSE 80

# Container entrypoint
CMD ["/start.sh"]
