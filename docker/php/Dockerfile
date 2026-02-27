# ============================================================
# Laravel 11 + PHP-FPM 8.2 + Supervisor (Windows compatible)
# ============================================================

# -------------------------------
# Stage 1: Build vendor
# -------------------------------
FROM php:8.2-cli AS vendor

RUN apt-get update && apt-get install -y \
    curl git unzip zip libzip-dev \
    && docker-php-ext-install zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install vendor (no dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts --no-progress \
    --apcu-autoloader || true

# -------------------------------
# Stage 2: PHP-FPM runtime
# -------------------------------
FROM php:8.2-fpm

# Set timezone system
RUN apt-get update && apt-get install -y tzdata \
    && ln -snf /usr/share/zoneinfo/Asia/Jakarta /etc/localtime \
    && echo "Asia/Jakarta" > /etc/timezone \
    && rm -rf /var/lib/apt/lists/*

# Set timezone PHP
RUN echo "date.timezone=Asia/Jakarta" > /usr/local/etc/php/conf.d/timezone.ini

# Install PHP extensions & deps
RUN apt-get update && apt-get install -y \
    zip unzip curl git supervisor libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libsodium-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mysqli mbstring gd zip sodium exif \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Custom PHP upload limits
RUN echo "upload_max_filesize=40M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=40M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/uploads.ini

# ============================================
# 🔥 Install Composer JUGA di runtime container
# ============================================
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy vendor dari stage vendor
COPY --from=vendor /app/vendor ./vendor
COPY --from=vendor /app/composer.lock ./composer.lock
COPY --from=vendor /app/composer.json ./composer.json

# Copy source code
COPY . .

# Supervisor config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup storage, symlink
RUN mkdir -p /var/log/supervisor \
    && mkdir -p storage/app/public bootstrap/cache \
    && rm -f public/storage \
    && ln -s /var/www/html/storage/app/public /var/www/html/public/storage \
    && chown -R www-data:www-data storage bootstrap/cache /var/log/supervisor \
    && chmod -R 775 storage bootstrap/cache /var/log/supervisor

# Package discover
RUN php artisan package:discover --ansi || true

# PHP-FPM config
RUN sed -i 's/^;*user = .*/user = www-data/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/^;*group = .*/group = www-data/' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's|^;*listen = .*|listen = 0.0.0.0:9000|' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's|^;*catch_workers_output = .*|catch_workers_output = yes|' /usr/local/etc/php-fpm.d/www.conf \
    && echo "php_admin_value[error_log] = /proc/self/fd/2" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

EXPOSE 9000

CMD ["php-fpm", "-F"]
