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

# Copy composer files first (for caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy the rest of the application
COPY . .

# Link storage (optional)
RUN php artisan storage:link || true

# Expose the port Render will use
ENV PORT=10000
EXPOSE 10000

# Start Laravel server (for testing; for production, nginx+php-fpm recommended)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]

# Install Node.js & npm
RUN apt-get install -y nodejs npm
RUN npm install
RUN npm run build
