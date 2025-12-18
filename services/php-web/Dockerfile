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

# 1. Копируем composer.json для кэширования зависимостей
COPY composer.json composer.lock* ./

# 2. Устанавливаем зависимости, но НЕ запускаем скрипты Laravel
RUN composer install --optimize-autoloader --no-dev --no-scripts

# 3. Копируем все остальные файлы проекта
COPY . .

# 4. Создаем .env файл из примера
RUN php -r "file_exists('.env') || copy('.env.example', '.env');"

# 5. Генерируем autoload БЕЗ запуска скриптов
RUN composer dump-autoload --optimize --no-scripts

# 6. Создаем необходимые директории
RUN mkdir -p storage/framework/{sessions,views,cache/data}

# 7. Устанавливаем права доступа (критически важно сделать это ДО запуска artisan)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Генерируем ключ приложения
RUN php artisan key:generate --ansi

# 9. Запускаем миграции для создания таблиц (`cache`, `jobs` и т.д.)
RUN php artisan migrate --force

# 10. Кэшируем конфигурацию, маршруты и вьюхи для максимальной скорости
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
# ---------------------------------------------------

CMD ["php-fpm"]