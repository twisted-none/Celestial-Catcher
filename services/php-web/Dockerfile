FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    oniguruma-dev

RUN docker-php-ext-install pdo pdo_pgsql pgsql zip mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock* ./

RUN composer install --optimize-autoloader --no-dev --no-scripts

COPY . .

RUN composer dump-autoload --optimize --no-scripts

RUN php -r "file_exists('.env') || copy('.env.example', '.env');"

RUN mkdir -p /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN php artisan key:generate --ansi

RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache


CMD ["php-fpm"]