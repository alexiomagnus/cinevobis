FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev unzip git libssl-dev pkg-config \
    && docker-php-ext-install pdo_mysql mysqli \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && a2enmod rewrite headers \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html