FROM php:8.1-fpm

WORKDIR /var/www/html

RUN apt-get update && \
    apt-get install -y \
        libfreetype6-dev \
        libjpeg-dev \
        libjpeg62-turbo-dev \
        libmagickwand-dev \
        libmcrypt-dev \
        libmemcached-dev \
        libpng-dev \
        libssl-dev \
        libxml2-dev \
        libz-dev \
        libzip-dev \
        nginx \
        vim \
        zip

# Install extentions
RUN docker-php-ext-install bcmath \
    exif \
    fileinfo \
    gd \
    pcntl \
    pdo \
    pdo_mysql \
    soap \
    zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
