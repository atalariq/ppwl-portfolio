#!/usr/bin/env bash
set -eu

[ -f .env ] || cp .env.example .env
[ -f .env.devcontainer ] || cp .env .env.devcontainer
sed -i \
    -e 's/^APP_ENV=.*/APP_ENV=devcontainer/' \
    -e 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/' \
    -e 's/^DB_HOST=.*/DB_HOST=db/' \
    -e 's/^DB_PORT=.*/DB_PORT=3306/' \
    -e 's/^DB_DATABASE=.*/DB_DATABASE=ppwl_portfolio/' \
    -e 's/^DB_USERNAME=.*/DB_USERNAME=ppwl/' \
    -e 's/^DB_PASSWORD=.*/DB_PASSWORD=secret/' \
    -e 's/^DB_URL=.*/DB_URL=/' \
    .env.devcontainer
grep -q '^DB_URL=' .env.devcontainer || printf '\nDB_URL=\n' >> .env.devcontainer

composer install --no-interaction --prefer-dist
npm ci
php artisan config:clear

if ! grep -q '^APP_KEY=base64:' .env.devcontainer; then
    php artisan key:generate
fi

php artisan migrate --seed
php artisan storage:link --force
npm run build
