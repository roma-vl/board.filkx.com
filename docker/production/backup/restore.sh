#!/bin/bash

set -e

# 🔧 Налаштування
MYSQL_CONTAINER="current-mysql-1"
DB_NAME="laravel"
DB_USER="sail"
DB_PASSWORD="password"

# 📦 Папка з бекапом (можна задати аргументом)
if [ -z "$1" ]; then
    echo "❗ Вкажи шлях до бекапу: ./restore.sh /opt/backups/2025-06-18_04-00"
    exit 1
fi

BACKUP_DIR="$1"

if [ ! -d "$BACKUP_DIR" ]; then
    echo "❌ Папка $BACKUP_DIR не існує"
    exit 1
fi

echo "♻️ Відновлюємо базу з $BACKUP_DIR/db.sql..."
docker exec -i "$MYSQL_CONTAINER" sh -c "mysql -u$DB_USER -p$DB_PASSWORD $DB_NAME" < "$BACKUP_DIR/db.sql"
echo "✅ БД відновлено"

echo "📂 Розпаковуємо файли у storage..."
tar -xzf "$BACKUP_DIR/files.tar.gz" -C /var/www/board.filkx.com/shared/storage/app/public
echo "✅ Файли розпаковано"

echo "🔐 Відновлюємо .env..."
cp "$BACKUP_DIR/.env" /var/www/html/.env
echo "✅ .env файл повернуто"

echo "🎉 Відновлення завершено!"
