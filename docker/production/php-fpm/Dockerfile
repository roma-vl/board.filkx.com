# Builder
FROM php:8.4-fpm AS builder

RUN apt-get update && apt-get install -y unzip git curl zip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

COPY composer.json composer.lock ./

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer \
    && composer install --no-dev --prefer-dist --optimize-autoloader --no-scripts

# Main
FROM php:8.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpq-dev zlib1g-dev zip libzip-dev libicu-dev vim git curl unzip \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo pdo_mysql intl bcmath opcache \
    && pecl install redis imagick msgpack \
    && docker-php-ext-enable redis imagick msgpack \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=builder /var/www/vendor ./vendor
COPY . ./

COPY ./docker/production/php-fpm/php.ini /usr/local/etc/php/conf.d/php.ini

RUN groupadd --force -g 1000 app \
    && useradd -ms /bin/bash --no-user-group -g 1000 -u 1337 app \
    && chown -R app:app /var/www

USER app

CMD ["php-fpm", "-F"]
