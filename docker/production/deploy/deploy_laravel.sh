#!/bin/bash
set -euo pipefail

COLOR=$1
APP_DIR="/var/www/board.filkx.com"
RELEASE_DIR="$APP_DIR/$COLOR"
DOCKER_COMPOSE_FILE="$RELEASE_DIR/docker-compose-production.yml"
WORKDIR_IN_CONTAINER="/var/www"

# -----------------------------
# Визначаємо протилежне сервісу
# -----------------------------
if [[ "$COLOR" == "blue" ]]; then
    OPPOSITE="green"
else
    OPPOSITE="blue"
fi

OPPOSITE_DIR="$APP_DIR/$OPPOSITE"
OPPOSITE_COMPOSE="$OPPOSITE_DIR/docker-compose-production.yml"

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
# Зупиняємо ПРОТИЛЕЖНЕ сервісу (це ключове!)
# -----------------------------
echo "🛑 Зупиняємо $OPPOSITE сервісу..."
if [ -f "$OPPOSITE_COMPOSE" ] && [ -d "$OPPOSITE_DIR" ]; then
    cd "$OPPOSITE_DIR"
    docker-compose -f "$OPPOSITE_COMPOSE" down -t 30 || true

    # Очікуємо повне зупинення протилежного
    echo "⏳ Очікуємо повного зупинення $OPPOSITE контейнерів..."
    for i in {1..15}; do
        RUNNING_CONTAINERS=$(docker-compose -f "$OPPOSITE_COMPOSE" ps -q | wc -l)
        if [ "$RUNNING_CONTAINERS" -eq 0 ]; then
            echo "✅ $OPPOSITE сервіс зупинено"
            break
        fi
        echo "⏳ Очікуємо зупинення $OPPOSITE... ($i/15)"
        sleep 3
    done

    # Форс-видалення залишкових контейнерів протилежного сервісу
    docker-compose -f "$OPPOSITE_COMPOSE" ps -aq | xargs -r docker rm -f 2>/dev/null || true
fi

# -----------------------------
# Перевіряємо вільні порти (тепер вони мають бути вільні)
# -----------------------------
echo "🔍 Перевіряємо вільні порти..."
sleep 5  # Додатковий таймаут для звільнення портів

if lsof -Pi :8082 -sTCP:LISTEN -t >/dev/null 2>&1; then
    echo "❌ Порт 8082 все ще зайнятий"
    lsof -Pi :8082
    # Форс-звільнення
    lsof -Pi :8082 -sTCP:LISTEN -t | xargs -r kill -9 2>/dev/null || true
    sleep 2
fi

# -----------------------------
# Switch до нового сервісу
# -----------------------------
cd "$RELEASE_DIR"

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

# -----------------------------
# Чекаємо базові сервіси
# -----------------------------
wait_for_container board-mysql "mysqladmin ping -h localhost"
wait_for_container board-redis "redis-cli ping"
wait_for_container board-elasticsearch "curl -s http://localhost:9200/_cluster/health | grep -E 'yellow|green'"

# -----------------------------
# Встановлюємо правильні права через root
# -----------------------------
# Використовуємо ID користувача app (1337) і групу (1000)
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -u root board-php-fpm sh -c "
  mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
  chmod -R 775 storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
  touch storage/logs/laravel-2025-11-16.log
  touch bootstrap/cache/.gitignore storage/framework/cache/.gitignore storage/framework/sessions/.gitignore storage/framework/views/.gitignore
  chown -R 1337:1000 storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
"

# -----------------------------
# Міграції та кеш
# -----------------------------
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan migrate --force
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan config:clear
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan config:cache
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan route:cache

# -----------------------------
# Storage link від root
# -----------------------------
docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -u root -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan storage:link

# -----------------------------
# Elasticsearch індексація
# -----------------------------
ELASTIC_CONTAINER=$(docker-compose -f "$DOCKER_COMPOSE_FILE" ps -q board-elasticsearch)
if [ -n "$ELASTIC_CONTAINER" ]; then
    # Додаткове очікування перед індексацією
    echo "⏳ Очікуємо повної готовності Elasticsearch..."
    for i in {1..30}; do
        STATUS=$(docker exec "$ELASTIC_CONTAINER" curl -s http://localhost:9200/_cluster/health | jq -r '.status' 2>/dev/null || echo "unknown")
        if [[ "$STATUS" == "yellow" || "$STATUS" == "green" ]]; then
            # Перевіряємо, чи можна виконати запит
            if docker exec "$ELASTIC_CONTAINER" curl -s http://localhost:9200/_cat/health >/dev/null 2>&1; then
                echo "✅ Elasticsearch повністю готовий"
                break
            fi
        fi
        echo "⏳ Очікуємо повної готовності ES... ($i/30)"
        sleep 5
    done

    # Перевіряємо статус ще раз перед індексацією
    STATUS=$(docker exec "$ELASTIC_CONTAINER" curl -s http://localhost:9200/_cluster/health | jq -r '.status' || echo "unknown")
    if [[ "$STATUS" == "yellow" || "$STATUS" == "green" ]]; then
        # Додатково перевіряємо доступність в Laravel контексті
        echo "🔍 Перевіряємо доступність ES з Laravel контексту..."
        docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan tinker --execute "
            try {
                \$client = app('elasticsearch');
                \$info = \$client->info();
                echo 'Elasticsearch доступний: ' . \$info['version']['number'] . PHP_EOL;
            } catch (Exception \$e) {
                echo 'ES недоступний: ' . \$e->getMessage() . PHP_EOL;
                exit(1);
            }
        " 2>/dev/null || {
            echo "⚠️ Elasticsearch ще не готовий для Laravel, пропускаємо індексацію"
            exit 0  # АБО продовжуємо без індексації
        }

        echo "🚀 Ініціалізація пошуку..."
        docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan search:init
        docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T -w "$WORKDIR_IN_CONTAINER" board-php-fpm php artisan search:reindex
    else
        echo "⚠️ Elasticsearch не готовий, пропускаємо індексацію"
    fi
fi

echo "✅ Деплой завершено. Активне середовище — $COLOR"
