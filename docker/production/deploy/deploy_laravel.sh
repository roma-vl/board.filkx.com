#!/bin/bash
set -e

COLOR=$1
APP_DIR="/var/www/board.filkx.com"
RELEASE_DIR="$APP_DIR/$COLOR/current"
DOCKER_COMPOSE_FILE="$RELEASE_DIR/docker-compose.yml"
WORKDIR_IN_CONTAINER="/var/www/html"

# ✅ Перевірки
if [[ "$COLOR" != "blue" && "$COLOR" != "green" ]]; then
  echo "❌ Некоректне середовище: $COLOR"
  exit 1
fi

if [ ! -d "$RELEASE_DIR" ]; then
  echo "❌ Папка релізу не знайдена: $RELEASE_DIR"
  exit 1
fi

echo "🚀 Деплой у $COLOR середовище"
cd "$RELEASE_DIR"

# 🔗 Shared user directories (adverts + banners)
rm -rf "$RELEASE_DIR/storage/app/public/adverts"
ln -sfn /var/www/board.filkx.com/shared/storage/app/public/adverts "$RELEASE_DIR/storage/app/public/adverts"

rm -rf "$RELEASE_DIR/storage/app/public/banners"
ln -sfn /var/www/board.filkx.com/shared/storage/app/public/banners "$RELEASE_DIR/storage/app/public/banners"

# 🔗 Shared .env
ln -sfn /var/www/board.filkx.com/shared/.env "$RELEASE_DIR/.env"

# 🔐 Права (до artisan migrate)
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test chown -R www-data:www-data storage bootstrap/cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test chmod -R 775 storage bootstrap/cache

# ⚙️ Міграції
echo "⚙️ Міграції..."
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan migrate --force

# 🧹 Кешування
echo "🧹 Кешування..."
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan config:clear
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan config:cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan route:cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan view:cache

# 🔗 Перемикаємо current
ln -sfn "$APP_DIR/$COLOR" "$APP_DIR/current"

echo "✅ Деплой завершено. Активне середовище — $COLOR"
