#!/bin/sh
set -eu

php artisan storage:link >/dev/null 2>&1 || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
