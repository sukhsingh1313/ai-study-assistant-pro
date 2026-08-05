FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

# Set Webroot to Laravel's public directory and enable Laravel Nginx routing & scripts
ENV WEBROOT=/var/www/html/public
ENV LARAVEL=true
ENV LERAVEL=true
ENV PHP_ERRORS_STDERR=1
ENV ERRORS=0
ENV RUN_SCRIPTS=1

# Copy application
COPY . /var/www/html

# Copy custom Nginx configuration
COPY conf/nginx-laravel.conf /etc/nginx/sites-available/default.conf
COPY conf/nginx-laravel.conf /etc/nginx/sites-enabled/default.conf

# Install PHP dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

# Create storage and bootstrap/cache directories with full permissions
RUN mkdir -p storage/framework/cache/data \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache \
    && chown -R nginx:nginx storage bootstrap/cache 2>/dev/null || true

# Make startup scripts executable
RUN chmod +x /var/www/html/scripts/*.sh 2>/dev/null || true

EXPOSE 80