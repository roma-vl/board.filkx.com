# 🚀 Інструкція з деплою проєкту через TeamCity

Цей документ описує кроки CI/CD деплою Laravel-проєкту `board.filkx.com` з використанням **TeamCity**, **Docker** і **Blue-Green Deployment**.

---

## ⚙️ Кроки CI/CD

### 1. 🛠️ Ініціалізація `.env`

Виконується скрипт, який:

- копіює production-файли;
- створює `.env`;
- експортує змінні середовища;
- фіксує поточний колір середовища (green/blue).

```
cp -r docker/production/8.4 docker/8.4
cp docker/production/docker-compose.yml docker-compose.yml
cp docker/production/vite.config.js vite.config.js

# Якщо current_color ще не існує
if [ ! -f "/teamcity-meta/current_color" ]; then
  echo "green" > "/teamcity-meta/current_color"
fi

# Створюємо .env
cat > .env <<EOF
APP_NAME=Board
APP_ENV=production
...
EOF

# Копіюємо в production
cp .env docker/production/.env

# Експортуємо змінні
export $(grep -v '^#' .env | xargs)
```

### 2. 📦 Встановлення PHP-залежностей

```
 composer install --no-dev --optimize-autoloader
```

### 🧱 Збірка фронтенду

```
cp docker/production/vite.config.js vite.config.js
npm ci && npm run build
cp public/build/.vite/manifest.json public/build/manifest.json
```

### 4. ✅ Лінтинг

```
npm install eslint-teamcity --no-save
npm run lint -- --format ./node_modules/eslint-teamcity/index.js
```

### 5. 🐳 Збірка Docker Compose

TeamCity використовує:

- docker-compose.yml
- docker/8.4/Dockerfile

### 6. 📤 Деплой на сервер

```
#!/bin/bash
set -e

REMOTE_USER="deploy"
REMOTE_HOST="IP"
REMOTE_COLOR_FILE="/teamcity-meta/current_color"
SSH_KEY="/home/buildagent/.ssh/id_rsa"

CURRENT_COLOR=$(cat "$REMOTE_COLOR_FILE")
if [ "$CURRENT_COLOR" == "green" ]; then
NEXT_COLOR="blue"
else
NEXT_COLOR="green"
fi

REMOTE_TARGET_DIR="/var/www/board.filkx.com/$NEXT_COLOR/current"

# Підготовка SSH
ssh-keyscan "$REMOTE_HOST" >> ~/.ssh/known_hosts

# Очистка і створення нової директорії
ssh -i "$SSH_KEY" "$REMOTE_USER@$REMOTE_HOST" "rm -rf $REMOTE_TARGET_DIR && mkdir -p $REMOTE_TARGET_DIR"

# Синхронізація файлів
rsync -e "ssh -i $SSH_KEY" -avz "$LOCAL_BUILD_DIR/" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_TARGET_DIR/"

# Запуск скрипту деплою Laravel
ssh -i "$SSH_KEY" "$REMOTE_USER@$REMOTE_HOST" "bash $REMOTE_TARGET_DIR/docker/production/deploy/deploy_laravel.sh $NEXT_COLOR"

# Оновлення активного кольору
echo "$NEXT_COLOR" > "$REMOTE_COLOR_FILE"
```
