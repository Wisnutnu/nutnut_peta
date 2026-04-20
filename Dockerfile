FROM php:8.1-cli

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-install zip

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install

CMD php -S 0.0.0.0:10000 -t public