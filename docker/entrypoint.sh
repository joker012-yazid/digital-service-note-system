#!/bin/sh
set -e

if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist
fi

if [ ! -f .env ]; then
    cp .env.example .env
fi

session_driver="${SESSION_DRIVER:-file}"
if grep -q '^SESSION_DRIVER=' .env; then
    sed -i "s/^SESSION_DRIVER=.*/SESSION_DRIVER=${session_driver}/" .env
else
    printf '\nSESSION_DRIVER=%s\n' "$session_driver" >> .env
fi

if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --ansi --force
fi

php artisan migrate --force
php artisan db:seed --class=DefaultSettingsSeeder --force

exec php artisan serve --host=0.0.0.0 --port=8000
