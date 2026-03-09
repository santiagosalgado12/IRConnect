#!/bin/bash
LOG="/var/log/webhook.log"

echo "=== Iniciando Deploy $(date) ===" >> $LOG

cd /var/www/html/irconnect || {
  echo "Error: Directorio no encontrado" >> $LOG
  exit 1
}

# Git safe dir
git config --global --add safe.directory /var/www/html/irconnect

# Pull cambios
git fetch --all
git reset --hard origin/main
git pull origin main >> $LOG 2>&1

# Permisos solo donde se necesita
chown -R www-data:www-data writable/

# Reiniciar Apache


echo "=== Deploy completado ===" >> $LOG



