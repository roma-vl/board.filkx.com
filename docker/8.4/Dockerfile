FROM ubuntu:24.04

LABEL maintainer="Roma Volkov"

ARG WWWGROUP=1000
ARG NODE_VERSION=20

WORKDIR /var/www
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

# Оновлення системи та встановлення базових пакетів
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && echo 'Acquire::http::Pipeline-Depth "0"; Acquire::http::No-Cache "true"; Acquire::BrokenProxy "true";' > /etc/apt/apt.conf.d/99custom \
    && apt-get update \
    && apt-get install -y gnupg curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python3 dnsutils librsvg2-bin fswatch ffmpeg nano \
    && apt-get install -y nginx \
    && mkdir -p /etc/apt/keyrings \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0xb8dc7e53946656efbce4c1dd71daeaab4ad4cab6' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu noble main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
    && apt-get update \
    && apt-get install -y php8.4-cli php8.4-fpm php8.4-dev php8.4-mysql php8.4-pgsql php8.4-sqlite3 php8.4-curl php8.4-mbstring php8.4-xml php8.4-zip php8.4-bcmath php8.4-intl php8.4-readline php8.4-redis php8.4-xdebug php8.4-imagick php8.4-ldap php8.4-msgpack nodejs \
    && npm install -g npm pnpm bun yarn \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*


# Встановлення капабілітів для PHP (для запуску на портах < 1024)
RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.4

# Створення користувача
RUN groupadd --force -g $WWWGROUP app \
    && useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 app

# Копіювання конфігурацій
COPY nginx.conf /etc/nginx/sites-available/default
COPY php.ini /etc/php/8.4/cli/conf.d/99-laravel.ini
COPY php.ini /etc/php/8.4/fpm/conf.d/99-laravel.ini
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Створення директорій для логів
RUN mkdir -p /var/log/supervisor /var/log/nginx /var/log/php \
    && chown -R app:app /var/www /var/log

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
