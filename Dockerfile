# ---------------------------------------------------------------
# Dockerfile — AprilianaFast Laravel Landing Page
# Dibuat untuk deploy di Railway (atau platform Docker lain)
# ---------------------------------------------------------------

FROM php:8.3-cli-alpine

# 1. Install system dependencies & PHP extensions yang dibutuhkan Laravel
RUN apk add --no-cache \
        git \
        curl \
        zip \
        unzip \
        libzip-dev \
        oniguruma-dev \
        icu-dev \
        libpng-dev \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        zip \
        intl \
        gd

# 2. Copy Composer dari image resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# 3. Copy seluruh source code project
COPY . .

# 4. Install dependency PHP (production, tanpa dev packages)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# 5. Set permission folder yang wajib writable oleh Laravel
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database

# 6. Copy entrypoint script
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]