# PHP
FROM php:8.5.2-fpm

# Dependências do PHP
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Ajustar permissões
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

WORKDIR /var/www/html