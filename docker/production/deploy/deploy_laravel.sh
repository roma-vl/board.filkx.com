#!/bin/bash
set -euo pipefail

COLOR=$1
APP_DIR="/var/www/board.filkx.com"
RELEASE_DIR="$APP_DIR/$COLOR"
DOCKER_COMPOSE_FILE="$RELEASE_DIR/docker-compose-production.yml"
WORKDIR_IN_CONTAINER="/var/www"

# -----------------------------
# Валідація аргументів
# -----------------------------
if [[ "$COLOR" != "blue" && "$COLOR" != "green" ]]; then
    echo "❌ Некоректне середовище: $COLOR"
    exit 1
fi

if [ ! -d "$RELEASE_DIR" ]; then
    echo "❌ Папка релізу не знайдена: $RELEASE_DIR"
    exit 1
fi

echo "🚀 Деплой у $COLOR середовище"

# -----------------------------
# Shared storage та .env
# -----------------------------
# Видаляємо існуючі директорії/посилання перед створенням нових
rm -rf "$RELEASE_DIR/storage/app/public/adverts"
rm -rf "$RELEASE_DIR/storage/app/public/banners"
rm -f "$RELEASE_DIR/.env"

ln -sfn "$APP_DIR/shared/storage/app/public/adverts" "$RELEASE_DIR/storage/app/public/adverts"
ln -sfn "$APP_DIR/shared/storage/app/public/banners" "$RELEASE_DIR/storage/app/public/banners"
ln -sfn "$APP_DIR/shared/.env" "$RELEASE_DIR/.env"

# -----------------------------
# Зупиняємо поточні контейнери
# -----------------------------
cd "$RELEASE_DIR"
docker-compose -f "$DOCKER_COMPOSE_FILE" down || true

# -----------------------------
# Atomic switch для current
# -----------------------------
ln -sfn "$RELEASE_DIR" "$APP_DIR/current"

# -----------------------------
# Старт контейнерів
# -----------------------------
docker-compose -f "$DOCKER_COMPOSE_FILE" up -d

# -----------------------------
# Функція чекера сервісів
# -----------------------------
wait_for_container() {
    local name=$1
    local cmd=$2
    local retries=${3:-30}
    local delay=${4:-2}

    CONTAINER_ID=$(docker-compose -f "$DOCKER_COMPOSE_FILE" ps -q "$name")
    if [ -z "$CONTAINER_ID" ]; then
        echo "❌ Контейнер $name не знайдено"
        exit 1
    fi

    for i in $(seq 1 $retries); do
        if docker exec "$CONTAINER_ID" sh -c "$cmd" >/dev/null 2>&1; then
            echo "✅ $name готовий"
            return 0
        fi
        echo "⏳ Очікуємо $name ($i/$retries)..."
        sleep $delay
    done

    echo "❌ $name не відповідає після $retries спроб"
    exit 1
}

# -----------------------------
# Чекаємо базові сервіси
# -----------------------------
wait_for_container mysql "mysqladmin ping -h localhost"
wait_for_container redis "redis-cli ping"
wait_for_container elasticsearch "curl -s http://localhost:9200/_cluster/health | grep -E 'yellow|green'"

# -----------------------------
# Права всередині контейнера (тільки на необхідні директорії)
# -----------------------------
# Створюємо необхідні директорії та встановлюємо права
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm sh -c "
  # Створюємо необхідні директорії якщо їх немає
  mkdir -p storage/logs storage/framework/cache storage/cache bootstrap/cache
  # Змінюємо права тільки на ці директорії
  find storage/logs storage/framework/cache storage/cache bootstrap/cache -type d -exec chmod 775 {} \;
  find storage/logs storage/framework/cache storage/cache bootstrap/cache -type f -exec chmod 664 {} \;
"

# -----------------------------
# Міграції та кеш
# -----------------------------
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan migrate --force
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan config:clear
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan config:cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan route:cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan view:cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan storage:link

# -----------------------------
# Elasticsearch індексація
# -----------------------------
ELASTIC_CONTAINER=$(docker-compose -f "$DOCKER_COMPOSE_FILE" ps -q elasticsearch)
if [ -n "$ELASTIC_CONTAINER" ]; then
    STATUS=$(docker exec "$ELASTIC_CONTAINER" curl -s http://localhost:9200/_cluster/health | jq -r '.status' || echo "unknown")
    if [[ "$STATUS" == "yellow" || "$STATUS" == "green" ]]; then
        docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan search:init
        docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan search:reindex
    fi
fi

echo "✅ Деплой завершено. Активне середовище — $COLOR"
