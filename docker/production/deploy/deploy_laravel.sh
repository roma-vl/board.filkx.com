#!/bin/bash
set -e

# 🔧 Шлях до прод-апки
APP_DIR="/var/www/board.filkx.com"
CURRENT_COLOR_FILE="/home/deploy/releases/current/teamcity-meta/current_color"
BLUE_DIR="$APP_DIR/blue"
GREEN_DIR="$APP_DIR/green"
BUILD_SOURCE="/opt/buildagent/work/40627a24d1766524"


# Якщо файл кольору не існує — створимо
if [ ! -f "$CURRENT_COLOR_FILE" ]; then
  echo "green" > "$CURRENT_COLOR_FILE"
fi

CURRENT_COLOR=$(cat "$CURRENT_COLOR_FILE")

if [ "$CURRENT_COLOR" != "green" ]; then
  NEXT_COLOR="blue"
  COLOR_DIR="$BLUE_DIR"
else
  NEXT_COLOR="green"
  COLOR_DIR="$GREEN_DIR"
fi

RELEASE_DIR="$COLOR_DIR/current"
DOCKER_COMPOSE_FILE="$RELEASE_DIR/docker-compose.yml"
WORKDIR_IN_CONTAINER="/var/www/html"


echo "➡️ Деплой у $NEXT_COLOR середовище…"
echo "📁 Цільова папка: $RELEASE_DIR"

# 🔄 Очистити цільову папку та створити нову
rm -rf "$RELEASE_DIR"
mkdir -p "$RELEASE_DIR"

# 📦 Скопіювати білд
cp -r "$BUILD_SOURCE/." "$RELEASE_DIR"

# ⚙️ Laravel-команди
cd "$RELEASE_DIR"

# ⚙️ Laravel-команди через контейнер
docker-compose -f "$RELEASE_DIR/docker-compose.yml" ps

# Запускаємо composer всередині контейнера
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test composer install --no-interaction --prefer-dist --optimize-autoloader

# ⚙️ Запуск міграцій
echo "⚙️ Виконуємо міграції бази даних…"
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan migrate --force

# 🧹 Очистка кешу та кешування конфігів
echo "🧹 Очищення кешу та кешування конфігів…"
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan config:clear
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan config:cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan route:cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan view:cache


echo "🔐 Права доступу"
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 🔗 Перемикаємо лінк
ln -sfn "$COLOR_DIR" "$APP_DIR/current"

# 💾 Записати новий колір
echo "$NEXT_COLOR" > "$CURRENT_COLOR_FILE"

echo "✅ Деплой завершено. Активне середовище — $NEXT_COLOR"
