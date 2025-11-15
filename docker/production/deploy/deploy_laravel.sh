#!/bin/bash
set -e

COLOR=$1
APP_DIR="/var/www/board.filkx.com"
RELEASE_DIR="$APP_DIR/$COLOR"
DOCKER_COMPOSE_FILE="$RELEASE_DIR/docker/production/docker-compose.yml"
WORKDIR_IN_CONTAINER="/var/www"

# Валідація
if [[ "$COLOR" != "blue" && "$COLOR" != "green" ]]; then
    echo "❌ Некоректне середовище: $COLOR"
    exit 1
fi

if [ ! -d "$RELEASE_DIR" ]; then
    echo "❌ Папка релізу не знайдена: $RELEASE_DIR"
    exit 1
fi

echo "🚀 Деплой у $COLOR середовище"

# 🔗 Shared storage і .env
ln -sfn /var/www/board.filkx.com/shared/storage/app/public/adverts "$RELEASE_DIR/storage/app/public/adverts"
ln -sfn /var/www/board.filkx.com/shared/storage/app/public/banners "$RELEASE_DIR/storage/app/public/banners"
ln -sfn /var/www/board.filkx.com/shared/.env "$RELEASE_DIR/.env"

# 🛑 Зупиняємо поточні контейнери
cd "$RELEASE_DIR"
docker-compose -f "$DOCKER_COMPOSE_FILE" down || true

# 🔗 Перемикаємо current (atomic)
ln -sfn "$RELEASE_DIR" "$APP_DIR/current"

# 🚀 Старт контейнерів
docker-compose -f "$DOCKER_COMPOSE_FILE" up -d

# ⏳ Очікуємо базові сервіси
sleep 5
for i in {1..30}; do
    MYSQL_CONTAINER=$(docker-compose -f "$DOCKER_COMPOSE_FILE" ps -q mysql)
    if [ -n "$MYSQL_CONTAINER" ]; then
        STATUS=$(docker inspect --format='{{.State.Health.Status}}' "$MYSQL_CONTAINER")
        if [[ "$STATUS" == "healthy" ]]; then break; fi
    fi
    sleep 2
done

REDIS_CONTAINER=$(docker-compose -f "$DOCKER_COMPOSE_FILE" ps -q redis)
if [ -n "$REDIS_CONTAINER" ]; then
    for i in {1..30}; do
        if docker exec "$REDIS_CONTAINER" redis-cli ping >/dev/null 2>&1; then break; fi
        sleep 2
    done
fi

# 🔐 Права всередині контейнера
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.app chown -R www-data:www-data storage bootstrap/cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.app chmod -R 775 storage bootstrap/cache

# ⚙️ Міграції та кеш
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.app php artisan migrate --force
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.app php artisan config:clear
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.app php artisan config:cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.app php artisan route:cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.app php artisan view:cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.app php artisan storage:link

# Elasticsearch
ELASTIC_CONTAINER=$(docker-compose -f "$DOCKER_COMPOSE_FILE" ps -q elasticsearch)
if [ -n "$ELASTIC_CONTAINER" ]; then
    for i in {1..30}; do
        STATUS=$(docker exec "$ELASTIC_CONTAINER" curl -s http://localhost:9200/_cluster/health | jq -r '.status' || echo "unknown")
        if [[ "$STATUS" == "yellow" || "$STATUS" == "green" ]]; then
            docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.app php artisan search:init
            docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" laravel.app php artisan search:reindex
            break
        fi
        sleep 2
    done
fi

echo "✅ Деплой завершено. Активне середовище — $COLOR"
