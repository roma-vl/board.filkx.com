#!/bin/bash
set -e

COLOR=$1
APP_DIR="/var/www/board.filkx.com"
RELEASE_DIR="$APP_DIR/$COLOR"
DOCKER_COMPOSE_FILE="/var/www/board.filkx.com/current/docker/production/docker-compose.yml"
WORKDIR_IN_CONTAINER="/var/www"

if [[ "$COLOR" != "blue" && "$COLOR" != "green" ]]; then
  echo "❌ Некоректне середовище: $COLOR"
  exit 1
fi

if [ ! -d "$RELEASE_DIR" ]; then
  echo "❌ Папка релізу не знайдена: $RELEASE_DIR"
  exit 1
fi

echo "🚀 Деплой у $COLOR середовище"

# 🔗 Shared user directories
ln -sfn /var/www/board.filkx.com/shared/storage/app/public/adverts "$RELEASE_DIR/storage/app/public/adverts"
ln -sfn /var/www/board.filkx.com/shared/storage/app/public/banners "$RELEASE_DIR/storage/app/public/banners"

# 🔗 Shared .env
ln -sfn /var/www/board.filkx.com/shared/.env "$RELEASE_DIR/.env"

# 🛑 Зупиняємо поточний контейнер
echo "🛑 Зупинка контейнерів..."
cd "$RELEASE_DIR"
docker-compose -f "$DOCKER_COMPOSE_FILE" down

# 🔗 Перемикаємо current **до старту контейнерів**
ln -sfn "$APP_DIR/$COLOR" "$APP_DIR/current"

# 🚀 Старт контейнерів
echo "🚀 Старт контейнерів..."
docker-compose -f "$DOCKER_COMPOSE_FILE" up -d

# 🔄 Чекаємо повного старту контейнерів
echo "⏳ Очікуємо запуск контейнерів..."
sleep 5

# 🔄 Чекаємо MySQL
MYSQL_CONTAINER=$(docker-compose -f "$DOCKER_COMPOSE_FILE" ps -q mysql)
if [ -n "$MYSQL_CONTAINER" ]; then
    echo "⏳ Очікуємо MySQL..."
    for i in {1..30}; do
        STATUS=$(docker inspect --format='{{.State.Health.Status}}' "$MYSQL_CONTAINER")
        if [[ "$STATUS" == "healthy" ]]; then
            echo "✅ MySQL готовий"
            break
        fi
        echo "🔄 MySQL статус: $STATUS (спроба $i)"
        sleep 2
    done
fi

# 🔐 Встановлюємо права всередині контейнера
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test chown -R www-data:www-data storage bootstrap/cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test chmod -R 775 storage bootstrap/cache

# ⚙️ Міграції
echo "⚙️ Міграції..."
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan migrate --force

# 🧹 Кешування
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan config:clear
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan config:cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan route:cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan view:cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan storage:link

# 🔗 Перемикаємо симлінк current **після успішних міграцій**
ln -sfn "$RELEASE_DIR" "$APP_DIR/current"

# Elasticsearch
echo "⏳ Очікуємо повну готовність Elasticsearch..."
ELASTIC_CONTAINER=$(docker-compose -f "$DOCKER_COMPOSE_FILE" ps -q elasticsearch)
for i in {1..30}; do
    STATUS=$(docker exec "$ELASTIC_CONTAINER" curl -s http://localhost:9200/_cluster/health | jq -r '.status' || echo "unknown")
    if [[ "$STATUS" == "yellow" || "$STATUS" == "green" ]]; then
        echo "✅ Elasticsearch статус: $STATUS"
        docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan search:init
        docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.test php artisan search:reindex
        break
    fi
    echo "🔄 Elasticsearch статус: $STATUS (спроба $i)"
    sleep 2
done

echo "✅ Деплой завершено. Активне середовище — $COLOR"
