#!/bin/bash

set -e  # ⛑️ Завалює весь скрипт, якщо хоч щось йде не так

TIMESTAMP=$(date +"%F_%H-%M")
BACKUP_DIR="/opt/backups/$TIMESTAMP"
MYSQL_CONTAINER="mysql"
DB_NAME=""
DB_USER=""
DB_PASSWORD=""

echo "📦 Створюємо директорію для бекапу: $BACKUP_DIR"
mkdir -p "$BACKUP_DIR"

echo "⏳ Бекап MySQL..."
docker exec "$MYSQL_CONTAINER" sh -c "mysqldump -u$DB_USER -p$DB_PASSWORD $DB_NAME" > "$BACKUP_DIR/db.sql"
echo "✅ MySQL збережено у $BACKUP_DIR/db.sql"

echo "📂 Бекап файлів (storage)..."
tar -czf "$BACKUP_DIR/files.tar.gz" /var/www/storage/app/public
echo "✅ Файли збережено у $BACKUP_DIR/files.tar.gz"

echo "🔐 Бекап .env..."
cp /var/www/.env "$BACKUP_DIR/.env"
echo "✅ .env збережено"

echo "🎉 Бекап завершено успішно! 🔒 Усе збережено в: $BACKUP_DIR"
