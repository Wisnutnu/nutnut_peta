FROM php:8.1-cli

WORKDIR /app

# Install dependencies sistem + extension PHP
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install zip intl mbstring xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install dependency CI4
RUN composer install --no-dev --optimize-autoloader

# Run server
CMD php -S 0.0.0.0:10000 -t public