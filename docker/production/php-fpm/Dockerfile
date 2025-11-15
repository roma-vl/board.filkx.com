# Builder stage для composer
FROM php:8.4-fpm AS builder

RUN apt-get update && apt-get install -y unzip git curl zip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY composer.json composer.lock ./

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer \
    && composer install --no-dev --prefer-dist --optimize-autoloader --no-scripts

# Основний stage
FROM php:8.4-fpm

WORKDIR /app

# PHP-розширення
RUN apt-get update && apt-get install -y \
    libpq-dev zlib1g-dev zip libzip-dev libicu-dev vim git curl unzip \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo pdo_pgsql intl bcmath opcache \
    && pecl install redis imagick msgpack \
    && docker-php-ext-enable redis imagick msgpack \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=builder /app/vendor ./vendor
COPY ./ ./

# PHP-конфіг
COPY ./docker/production/php/default.ini /usr/local/etc/php/conf.d/default.ini

# Користувач і права
RUN groupadd --force -g 1000 app \
    && useradd -ms /bin/bash --no-user-group -g 1000 -u 1337 app \
    && mkdir -p /app/storage /app/bootstrap/cache \
    && chown -R app:app /app

USER app

EXPOSE 9000
CMD ["php-fpm", "-F"]
