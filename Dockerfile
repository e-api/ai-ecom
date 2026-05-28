ARG PHP_VERSION=8.5
FROM php:${PHP_VERSION}-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libxpm-dev \
    curl \
    unzip \
    git \
    && docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    && docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    pdo_mysql \
    mysqli \
    zip \
    bcmath \
    mbstring \
    xml \
    intl \
    soap \
    gd \
    gettext \
    pcntl \
    shmop \
    sockets \
    sysvsem \
    ftp \
    exif \
    calendar \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www