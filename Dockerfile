FROM php:8.2-apache

# Estensioni necessarie
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install pdo pdo_mysql mysqli mbstring zip

# Abilita mod_rewrite per Apache
RUN a2enmod rewrite

# Copia configurazione PHP custom
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# Imposta la document root
ENV APACHE_DOCUMENT_ROOT /var/www/html

WORKDIR /var/www/html