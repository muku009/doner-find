# Use official PHP 8.2 with FPM
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy the full project
COPY . .

# Build frontend
RUN apt-get install -y nodejs npm
RUN npm install --legacy-peer-deps
RUN npm run build

# Storage link
RUN php artisan storage:link || true

# Expose port
ENV PORT=10000
EXPOSE 10000

# ---- MOST IMPORTANT FIX ----
CMD bash -lc "php artisan serve --host=0.0.0.0 --port=$PORT"
