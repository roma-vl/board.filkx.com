FROM php:8.4-fpm

LABEL maintainer="Roma Volkov"
ARG WWWGROUP=1000
ENV TZ=UTC
ENV DEBIAN_FRONTEND=noninteractive

WORKDIR /var/www

# Базові пакети і PHP-розширення
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libzip-dev libicu-dev libonig-dev librsvg2-bin ffmpeg \
    && docker-php-ext-install pdo_mysql mbstring xml zip intl bcmath \
    && pecl install redis imagick msgpack \
    && docker-php-ext-enable redis imagick msgpack \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Користувач
RUN groupadd --force -g $WWWGROUP app \
    && useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 app

# Права
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chown -R app:app /var/www

# Конфіг php
COPY php.ini /usr/local/etc/php/conf.d/99-laravel.ini

USER app

EXPOSE 9000

CMD ["php-fpm"]
