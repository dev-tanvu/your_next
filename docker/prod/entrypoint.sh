#!/bin/sh
set -e

# Production container entrypoint.
# Runs once on container start, then hands off to the CMD (php-fpm).

cd /var/www/html

# Laravel's storage/framework/* and bootstrap/cache dirs are git-ignored, so they're
# absent from the image (and from a fresh storage volume). Create them before any
# artisan command boots the framework, or the Blade compiler aborts with
# "Please provide a valid cache path."
mkdir -p \
    storage/framework/views \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/logs \
    bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Sync the built public/ into the volume shared with the Nginx container, so Nginx
# can serve static assets directly. (The volume may start empty on first boot.)
if [ -d /var/www/html/public ]; then
    cp -r /var/www/html/public/. /var/www/html/public-shared/ 2>/dev/null || true
fi

# Cache framework config/routes/views for performance (safe to re-run).
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# NOTE: migrations are intentionally NOT run here — the deploy pipeline runs
# `php artisan migrate --force` explicitly after the stack is up (see DEPLOYMENT.md),
# so that a failed migration doesn't crash-loop the container.

exec "$@"
