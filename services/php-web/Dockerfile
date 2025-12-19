FROM php:8.4-fpm-alpine

# Установка системных зависимостей и драйверов
RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    oniguruma-dev

# Установка PHP расширений
RUN docker-php-ext-install pdo pdo_pgsql pgsql zip mbstring

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 1. Копируем только composer.json, чтобы кэшировать зависимости
COPY composer.json composer.lock* ./

# 2. Устанавливаем зависимости, НЕ ЗАПУСКАЯ скрипты Laravel
RUN composer install --optimize-autoloader --no-dev --no-scripts

# 3. Теперь копируем все остальные файлы проекта (включая artisan)
COPY . .

# --- ФИКС ---
# 4. Генерируем autoload БЕЗ запуска скриптов
RUN composer dump-autoload --optimize --no-scripts

# 5. Создаем .env файл
RUN php -r "file_exists('.env') || copy('.env.example', '.env');"

# 6. Права доступа (!!! ПЕРЕМЕСТИЛИ СЮДА !!!)
# Laravel'у нужны права на запись ДО запуска artisan команд
# 6. Права доступа
# Создаем директорию, если она не существует, и сразу выставляем права
RUN mkdir -p /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Генерируем ключ приложения
RUN php artisan key:generate --ansi

RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache


CMD ["php-fpm"]