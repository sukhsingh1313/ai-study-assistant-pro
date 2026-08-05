# AI Study Assistant - Production Deployment Guide

This guide provides step-by-step instructions for deploying the **Laravel 12 AI Study Assistant** application to a production server environment (Linux / Nginx / MySQL / PHP 8.2+).

---

## 1. System Requirements & Environment Setup

- **PHP**: `>= 8.2` (Required extensions: `pdo_mysql`, `curl`, `mbstring`, `openssl`, `fileinfo`, `json`, `tokenizer`, `xml`)
- **Web Server**: Nginx or Apache with `mod_rewrite` enabled
- **Database**: MySQL `8.0+` or MariaDB `10.5+`
- **Composer**: `2.x`
- **SSL Certificate**: Let's Encrypt / Certbot

---

## 2. Server Preparation & Repository Setup

```bash
# Clone the project repository
cd /var/www
git clone https://github.com/your-org/ai-study-assistant.git
cd ai-study-assistant

# Set directory permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

---

## 3. Dependency Installation & Environment Configuration

```bash
# Install PHP dependencies without dev packages
composer install --no-dev --optimize-autoloader

# Copy production environment file
cp .env.example .env

# Generate application security key
php artisan key:generate
```

### Configure `.env` File Parameters:
```ini
APP_NAME="AI Study Assistant"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ai_study_assistant_prod
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

GEMINI_API_KEY=your_production_google_gemini_api_key
GEMINI_MODEL=gemini-2.0-flash
```

---

## 4. Database Migrations & Symlink Creation

```bash
# Run database migrations and seeders if required
php artisan migrate --force --seed

# Create public storage symlink for uploaded PDF & image note attachments
php artisan storage:link
```

---

## 5. Performance Optimization & Caching

Run the following Artisan optimization commands to cache configuration, routes, and Blade views:

```bash
# Cache configuration, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

## 6. Nginx Web Server Configuration

Create Nginx site configuration file at `/etc/nginx/sites-available/ai-study-assistant`:

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/ai-study-assistant/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site & reload Nginx:
```bash
ln -s /etc/nginx/sites-available/ai-study-assistant /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```

---

## 7. SSL Certificate Setup

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

---

## 8. Security Hardening Checklist

1. Ensure `APP_DEBUG=false` in `.env`.
2. Restrict `.env` file permissions: `chmod 600 .env`.
3. Configure HTTPS redirection.
4. Verify Gemini API key rate limits and API access scopes in Google Cloud Console.
