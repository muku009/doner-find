FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy whole project BEFORE composer install
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

RUN apt-get install -y nodejs npm
RUN npm install --legacy-peer-deps
RUN npm run build

RUN php artisan storage:link || true

ENV PORT=10000
EXPOSE 10000

CMD bash -lc "php artisan serve --host=0.0.0.0 --port=$PORT"
