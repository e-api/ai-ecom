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

# Security: disable dangerous PHP functions
RUN echo "disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,show_source,pcntl_exec" >> /usr/local/etc/php/php.ini

# Security: limit PHP-FPM to prevent resource exhaustion
RUN echo "pm.max_children = 10" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.start_servers = 3" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.min_spare_servers = 2" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_spare_servers = 5" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_requests = 300" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "request_terminate_timeout = 120s" >> /usr/local/etc/php-fpm.d/www.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www