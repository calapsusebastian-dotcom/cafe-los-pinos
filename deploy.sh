#!/usr/bin/env bash
# Actualiza el sitio en el servidor: ./deploy.sh
set -euo pipefail

cd "$(dirname "$0")"

git pull origin main

if git diff --name-only HEAD@{1} HEAD 2>/dev/null | grep -qE '^(composer\.(json|lock))$'; then
    composer install --no-dev --optimize-autoloader
fi

php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Despliegue terminado."
