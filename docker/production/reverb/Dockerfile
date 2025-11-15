FROM php:8.4-cli

WORKDIR /var/www

# PHP-розширення
RUN apt-get update && apt-get install -y \
        zip unzip git libpng-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath gd intl

# Копіюємо composer + залежності
COPY composer.json composer.lock ./
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Копіюємо код
COPY . .

# Користувач
ARG WWWGROUP=1000
RUN groupadd -g $WWWGROUP app && useradd -ms /bin/bash -g $WWWGROUP app

USER app

CMD ["php", "/var/www/artisan", "reverb:start"]
