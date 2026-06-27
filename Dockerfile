# 1. Use the CLI image, NOT fpm
FROM php:8.4-cli

WORKDIR /var/www

# System deps
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files and install
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy the rest of the project
COPY . .

# Laravel permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# 2. Expose the port Coolify should look for
EXPOSE 8000

# 3. Start the HTTP server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]