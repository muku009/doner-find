FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy everything first (artisan must exist before composer)
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Install & build frontend
RUN npm install --legacy-peer-deps
RUN npm run build

# Storage link
RUN php artisan storage:link || true

# Expose port
ENV PORT=10000
EXPOSE 10000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]
