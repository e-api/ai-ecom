ARG PHP_VERSION=8.5
FROM php:${PHP_VERSION}-fpm

# Install system dependencies
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

# Security: disable dangerous PHP functions (keep proc_open for Composer)
RUN echo "disable_functions = exec,passthru,shell_exec,system,popen,curl_exec,curl_multi_exec,show_source,pcntl_exec" >> /usr/local/etc/php/php.ini

# Security: limit PHP-FPM
RUN echo "pm.max_children = 10" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.start_servers = 3" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.min_spare_servers = 2" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_spare_servers = 5" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "pm.max_requests = 300" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "request_terminate_timeout = 120s" >> /usr/local/etc/php-fpm.d/www.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy application files (excluding vendor from .dockerignore)
COPY . /var/www

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD php -r "exit(0);"

EXPOSE 9000

CMD ["php-fpm"]