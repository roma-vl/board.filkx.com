FROM php:8.4-cli

WORKDIR /var/www

# PHP-розширення
RUN apt-get update && apt-get install -y zip unzip git libpng-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath gd intl

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Код
COPY . .

# Користувач
ARG WWWGROUP=1000
RUN groupadd -g $WWWGROUP app && useradd -ms /bin/bash -g $WWWGROUP app
USER app

CMD ["php", "/var/www/artisan", "reverb:start", "--port=8083"]
