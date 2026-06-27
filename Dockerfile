# 1. Install system dependencies required by Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

# 2. Copy ONLY the composer files first (this caches the dependency layer)
COPY composer.json composer.lock ./

# 3. Run composer install
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 4. Copy the rest of the application code
COPY . .

# 5. Run Composer scripts/autoloader optimization again now that code is present
RUN composer dump-autoload --optimize