# 📦 Laravel Docker Backup & Restore Guide

Цей документ описує процес **створення резервних копій** (бекапу) та **відновлення** проєкту Laravel (файлів, бази та `.env`) у середовищі Docker на VPS.

---

## 📁 Структура

- /opt/scripts/backup.sh # Скрипт для створення резервної копії
- /opt/scripts/restore.sh # Скрипт для відновлення з резервної копії
- /opt/backups/YYYY-MM-DD_HH-MM/ # Каталоги з архівами бекапу

---

## 🧰 Налаштування

- Laravel працює в Docker з контейнером MySQL.
- Файли зберігаються у: `/var/www/board.filkx.com/shared/storage/app/public`
- `.env` знаходиться в: `/var/www/html/.env`
- Контейнер MySQL називається: `mysql`

---

## 🕐 Cron-приклад (щоденний бекап о 4:00):

- crontab -e
- 0 4 \* \* \* /opt/scripts/backup.sh >> /var/log/backup.log 2>&1

---

## 🔐 Створення бекапу

### 📄 Файл: `/opt/scripts/backup.sh`

```bash
    #!/bin/bash

    set -e

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
    tar -czf "$BACKUP_DIR/files.tar.gz" /var/www/board.filkx.com/shared/storage/app/public
    echo "✅ Файли збережено у $BACKUP_DIR/files.tar.gz"

    echo "🔐 Бекап .env..."
    cp /var/www/html/.env "$BACKUP_DIR/.env"
    echo "✅ .env збережено"

    echo "🎉 Бекап завершено успішно! 🔒 Усе збережено в: $BACKUP_DIR"
```

### 🧪 Використання:

- chmod +x /opt/scripts/backup.sh
- /opt/scripts/backup.sh
- ***

## ♻️ Відновлення з бекапу

```bash
    #!/bin/bash

    set -e

    MYSQL_CONTAINER="current-mysql-1"
    DB_NAME="laravel"
    DB_USER="sail"
    DB_PASSWORD="password"

    if [ -z "$1" ]; then
    echo "❗ Вкажи шлях до бекапу: ./restore.sh /opt/backups/YYYY-MM-DD_HH-MM"
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
```

### 🧪 Використання:

- chmod +x /opt/scripts/restore.sh
- /opt/scripts/restore.sh /opt/backups/YYYY-MM-DD_HH-MM
