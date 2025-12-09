# Use PHP 8.2 FPM
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    libzip-dev nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy only composer files first (caching layer)
COPY composer.json composer.lock ./

# Install PHP dependencies (this is where your error occurs)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist || cat /var/www/html/storage/logs/laravel.log

# Copy the whole application
COPY . .

# Install & build frontend assets
RUN npm install --legacy-peer-deps
RUN npm run build

# Storage link
RUN php artisan storage:link || true

# Expose port for Render
ENV PORT=10000
EXPOSE 10000

# Start Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]
