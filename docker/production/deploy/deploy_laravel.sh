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
[ -e "$RELEASE_DIR/storage/app/public/adverts" ] && rm -rf "$RELEASE_DIR/storage/app/public/adverts"
ln -sfn /var/www/board.filkx.com/shared/storage/app/public/adverts "$RELEASE_DIR/storage/app/public/adverts"

[ -e "$RELEASE_DIR/storage/app/public/banners" ] && rm -rf "$RELEASE_DIR/storage/app/public/banners"
ln -sfn /var/www/board.filkx.com/shared/storage/app/public/banners "$RELEASE_DIR/storage/app/public/banners"

# 🔗 Shared .env
rm -f "$RELEASE_DIR/.env"
ln -sfn /var/www/board.filkx.com/shared/.env "$RELEASE_DIR/.env"

# 🛑 Зупинка поточних контейнерів
echo "🛑 Зупинка контейнерів..."
docker-compose -f "$DOCKER_COMPOSE_FILE" down

# 🚀 Запуск контейнерів у фоні
echo "🚀 Запуск контейнерів..."
docker-compose -f "$DOCKER_COMPOSE_FILE" up -d

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

docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan storage:link

echo "⏳ Очікуємо повну готовність Elasticsearch..."

for i in {1..30}; do
    STATUS=$(curl -s http://localhost:9200/_cluster/health | jq -r '.status')

    if [[ "$STATUS" == "yellow" || "$STATUS" == "green" ]]; then
        echo "✅ Elasticsearch статус: $STATUS"
        break
    fi

    echo "🔄 Статус: $STATUS (спроба $i)"
    sleep 2
done

# Якщо все ще не готовий — показати попередження, але не падати
FINAL_STATUS=$(curl -s http://localhost:9200/_cluster/health | jq -r '.status')
if [[ "$FINAL_STATUS" != "yellow" && "$FINAL_STATUS" != "green" ]]; then
    echo "⚠️ Elasticsearch досі не готовий (статус: $FINAL_STATUS). Пропускаємо search:init"
else
    docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan search:init
    docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan search:reindex
fi


# 🔗 Перемикаємо current
ln -sfn "$APP_DIR/$COLOR/current" "$APP_DIR/current"

echo "✅ Деплой завершено. Активне середовище — $COLOR"
