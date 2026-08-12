#!/usr/bin/env bash
set -e

composer install --no-dev --optimize-autoloader --no-interaction
npm ci --ignore-scripts
npm run build

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force --seed
php artisan storage:link || true
