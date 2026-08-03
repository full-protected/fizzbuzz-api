FROM php:8.4-fpm

ARG UID=1000
ARG GID=1000

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

# Création d'un utilisateur applicatif avec un UID/GID configurable,
# pour éviter les conflits de permissions avec les fichiers montés depuis l'hôte
RUN groupmod -g ${GID} www-data && \
    usermod -u ${UID} -g ${GID} www-data && \
    chown -R www-data:www-data /var/www

WORKDIR /var/www/html

USER www-data
