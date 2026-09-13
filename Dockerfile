# syntax=docker/dockerfile:1

# ---- Stage 1: Composer dependencies ----
FROM php:8.4-cli-alpine AS vendor

RUN apk add --no-cache \
        postgresql-dev \
        sqlite-dev \
        oniguruma-dev \
        libzip-dev \
        zip \
        unzip \
        icu-dev

RUN docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pdo_sqlite \
        mbstring \
        zip \
        bcmath \
        intl \
        opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader

COPY . .

RUN composer dump-autoload --optimize --no-dev --no-scripts

# ---- Stage 2: runtime image ----
FROM php:8.4-cli-alpine

RUN apk add --no-cache \
        postgresql-dev \
        sqlite-dev \
        oniguruma-dev \
        libzip-dev \
        zip \
        unzip \
        icu-dev \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pdo_sqlite \
        mbstring \
        zip \
        bcmath \
        intl \
        opcache

WORKDIR /var/www/html

COPY --from=vendor /app /var/www/html

RUN mkdir -p storage/framework/{cache,sessions,testing,views} \
        storage/logs storage/app/public bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["entrypoint.sh"]
