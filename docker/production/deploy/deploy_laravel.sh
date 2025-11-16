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
# Перевіряємо чи є активні контейнери цього сервісу
# -----------------------------
CURRENT_SERVICES=$(docker ps --format "table {{.Names}}\t{{.Status}}" | grep -E "(blue|green)-board" || true)
if [ -n "$CURRENT_SERVICES" ]; then
    echo "📋 Активні контейнери перед деплоєм:"
    echo "$CURRENT_SERVICES"
fi

# -----------------------------
# Зупиняємо поточні контейнери з очікуванням
# -----------------------------
echo "🛑 Зупиняємо поточні контейнери..."
cd "$RELEASE_DIR"

# Зупиняємо з таймаутом і очікуванням
docker-compose -f "$DOCKER_COMPOSE_FILE" down -t 30 || true

# Очікуємо повне зупинення
echo "⏳ Очікуємо повного зупинення контейнерів..."
for i in {1..10}; do
    RUNNING_CONTAINERS=$(docker-compose -f "$DOCKER_COMPOSE_FILE" ps -q | wc -l)
    if [ "$RUNNING_CONTAINERS" -eq 0 ]; then
        echo "✅ Всі контейнери зупинено"
        break
    fi
    echo "⏳ Очікуємо зупинення... ($i/10)"
    sleep 3
done

# Форс-видалення будь-яких залишкових контейнерів
docker-compose -f "$DOCKER_COMPOSE_FILE" ps -aq | xargs -r docker rm -f 2>/dev/null || true

# -----------------------------
# Перевіряємо вільні порти
# -----------------------------
echo "🔍 Перевіряємо вільні порти..."
if lsof -Pi :8082 -sTCP:LISTEN -t >/dev/null 2>&1; then
    echo "❌ Порт 8082 все ще зайнятий"
    lsof -Pi :8082
    exit 1
fi

# -----------------------------
# Atomic switch для current
# -----------------------------
ln -sfn "$RELEASE_DIR" "$APP_DIR/current"

# -----------------------------
# Старт нових контейнерів
# -----------------------------
echo "🚀 Старт нових контейнерів..."
docker-compose -f "$DOCKER_COMPOSE_FILE" up -d --force-recreate

# -----------------------------
# Чекаємо базові сервіси (весь код залишається без змін)
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

# Весь інший код залишається без змін...
