FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    zip unzip nodejs npm

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm ci && npm run build

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 8080

CMD php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=8080
