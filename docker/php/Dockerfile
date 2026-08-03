FROM php:8.4-fpm

# Dépendances système nécessaires à la compilation des extensions PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Extensions PHP nécessaires à l'application
RUN docker-php-ext-install \
    pdo_pgsql \
    intl \
    zip \
    opcache

# Installation de Composer (copié depuis l'image officielle Composer)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# L'utilisateur www-data existe déjà dans l'image de base php:8.4-fpm
USER www-data
