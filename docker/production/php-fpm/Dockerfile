FROM ubuntu:24.04

LABEL maintainer="Roma Volkov"
ARG WWWGROUP=1000
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

WORKDIR /var/www

# Базові пакети + PHP
RUN apt-get update && apt-get install -y gnupg curl ca-certificates \
    && mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://packages.sury.org/php/apt.gpg | gpg --dearmor -o /etc/apt/keyrings/sury-php.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/sury-php.gpg] https://packages.sury.org/php/ noble main" \
       > /etc/apt/sources.list.d/sury-php.list \
    && apt-get update \
    && apt-get install -y php8.4-cli php8.4-fpm php8.4-mysql php8.4-pgsql php8.4-sqlite3 php8.4-curl php8.4-mbstring php8.4-xml php8.4-zip php8.4-bcmath php8.4-intl php8.4-readline php8.4-redis php8.4-imagick php8.4-ldap php8.4-msgpack composer \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*


# Користувач
RUN groupadd --force -g $WWWGROUP app \
    && useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 app

# Права
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chown -R app:app /var/www \

COPY php.ini /etc/php/8.4/fpm/conf.d/99-laravel.ini

USER app

EXPOSE 9000

CMD ["php-fpm8.4", "-F"]
