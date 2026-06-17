FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libzip-dev libpng-dev libonig-dev libxml2-dev

RUN docker-php-ext-install \
    pdo pdo_mysql mbstring zip exif pcntl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD ["sh", "-c", "\
    php artisan config:clear && \
    php artisan migrate --force --no-interaction && \
    php artisan db:seed --force --no-interaction && \
    php artisan storage:link --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080} \
"]