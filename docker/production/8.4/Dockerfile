# Stage 1: build PHP + Node
FROM php:8.4-fpm AS build

WORKDIR /var/www

# Системні пакети + PHP-розширення
RUN apt-get update && apt-get install -y \
        git unzip zip curl libpng-dev libonig-dev libxml2-dev \
        npm nodejs \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Кешування composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Кешування node_modules
COPY package*.json ./
RUN npm install

# Копіюємо весь код
COPY . .

# Node build
RUN npm run build

# Stage 2: production
FROM php:8.4-fpm

WORKDIR /var/www

# Системні пакети для продакшн
RUN apt-get update && apt-get install -y \
    nginx supervisor libpng-dev zip unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Користувач
ARG WWWGROUP=1000
RUN groupadd -g $WWWGROUP app \
    && useradd -ms /bin/bash -g $WWWGROUP app

# Копіюємо код + vendor + built assets
COPY --from=build /var/www /var/www

# Конфіги
COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /usr/local/etc/php/conf.d/99-laravel.ini

# Права
RUN chown -R app:app /var/www /var/log

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
