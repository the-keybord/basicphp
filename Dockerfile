FROM php:8.4-cli

WORKDIR /var/www

# Install system dependencies and Node.js (needed for compiling Vite assets)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install required PHP extensions for Laravel and MySQL
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy dependency files first to utilize Docker build caching layers
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy the rest of your application code
COPY . .

# Install JavaScript packages and build optimized assets for production
RUN npm install
RUN npm run build

# Clean up npm artifacts to keep the final container image small
RUN rm -rf node_modules

# Setup correct Linux storage and cache permissions for the web server
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

# Automated boot sequencing: Link storage, clear stale configs, run migrations, start app
CMD ["sh", "-c", "php artisan storage:link && php artisan config:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"]