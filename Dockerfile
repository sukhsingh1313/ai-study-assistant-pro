FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

# Set Webroot to Laravel's public directory
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV ERRORS=0
ENV RUN_SCRIPTS=1

# Copy application
COPY . /var/www/html

# Install PHP dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

# Explicitly create Laravel storage & cache subdirectories (avoiding shell brace expansion issues)
RUN mkdir -p storage/framework/cache/data \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache \
    && chown -R nginx:nginx storage bootstrap/cache 2>/dev/null || true

# Storage link
RUN php artisan storage:link || true

# Clear caches
RUN php artisan config:clear || true \
 && php artisan cache:clear || true \
 && php artisan route:clear || true \
 && php artisan view:clear || true

# Cache for production
RUN php artisan config:cache || true \
 && php artisan route:cache || true \
 && php artisan view:cache || true

# Health endpoint
RUN echo "OK" > /var/www/html/public/health

EXPOSE 80