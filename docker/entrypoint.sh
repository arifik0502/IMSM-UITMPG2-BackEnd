cat << 'ENTRYPOINT'
#!/bin/sh
set -e

cd /var/www/html

# APP_KEY, DB_*, FRONTEND_URL etc. are injected by Render as real env vars —
# there is NO .env file in this container by design, so anything that writes
# to .env (like `artisan key:generate`) will crash. If APP_KEY is missing,
# generate one in pure PHP instead and export it for this process only.
if [ -z "$APP_KEY" ]; then
    echo "WARNING: APP_KEY is not set. Generating one for this container run only —"
    echo "set a permanent APP_KEY in Render's environment variables instead,"
    echo "otherwise every restart invalidates existing sessions/cookies."
    export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"fi

php artisan package:discover --ansi
php artisan config:clear
php artisan migrate --force

# Only meaningful when ATTENDANCE_PHOTOS_DISK=public; harmless otherwise.
php artisan storage:link || true

php artisan config:cache
php artisan route:cache

echo "Starting server on 0.0.0.0:${PORT:-8000}"
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
ENTRYPOINT
Output

#!/bin/sh
set -e

cd /var/www/html

# APP_KEY, DB_*, FRONTEND_URL etc. are injected by Render as real env vars —
# there is NO .env file in this container by design, so anything that writes
# to .env (like `artisan key:generate`) will crash. If APP_KEY is missing,
# generate one in pure PHP instead and export it for this process only.
if [ -z "$APP_KEY" ]; then
    echo "WARNING: APP_KEY is not set. Generating one for this container run only —"
    echo "set a permanent APP_KEY in Render's environment variables instead,"
    echo "otherwise every restart invalidates existing sessions/cookies."
    export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
fi

php artisan package:discover --ansi
php artisan config:clear
php artisan migrate --force

# Only meaningful when ATTENDANCE_PHOTOS_DISK=public; harmless otherwise.
php artisan storage:link || true

php artisan config:cache
php artisan route:cache

echo "Starting server on 0.0.0.0:${PORT:-8000}"
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
