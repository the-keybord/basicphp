FROM php:8.2-fpm

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

# 1. Copy ONLY composer files first to leverage Docker layer caching
COPY composer.json composer.lock ./

# 2. Install dependencies (--no-scripts prevents Artisan from running before code is copied)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 3. Copy the rest of the project
COPY . .

# Laravel permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

# 4. Use JSON array syntax (exec form) to fix the JSONArgsRecommended warning
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]