#!/usr/bin/env bash
# ==========================================================================
# Script de despliegue / actualización en el servidor.
# Úsalo desde la raíz del proyecto:  bash deploy/deploy.sh
# (La primera vez asegúrate de tener el archivo .env ya configurado).
# ==========================================================================
set -euo pipefail

cd "$(dirname "$0")/.."

COMPOSE="docker compose -f docker-compose.prod.yml"
export HOST_UID="$(id -u)"
export HOST_GID="$(id -g)"

# Los archivos del proyecto pertenecen al usuario del host (HOST_UID), no a
# www-data (UID 33) que vive dentro de la imagen. Por eso ejecutamos artisan y
# composer como el dueño real: así pueden escribir vendor/, bootstrap/cache/ y
# storage/ sin chocar con permisos.
APP_EXEC="$COMPOSE exec -T -u ${HOST_UID}:${HOST_GID} -e HOME=/tmp app"

echo "==> 1/7 Trayendo últimos cambios (git pull)"
git config --global --add safe.directory "$PWD" 2>/dev/null || true
git pull --ff-only || echo "   (sin git o sin remoto; se omite)"

echo "==> 2/7 Construyendo imagen y levantando contenedores"
$COMPOSE build
$COMPOSE up -d

echo "==> 3/7 Instalando dependencias PHP (sin dev)"
$APP_EXEC composer install --no-dev --optimize-autoloader --no-interaction

echo "==> 4/7 Compilando assets (Vite)"
docker run --rm -u "$HOST_UID:$HOST_GID" \
    -e HOME=/tmp -e npm_config_cache=/tmp/.npm \
    -v "$PWD":/app -w /app node:22-alpine \
    sh -c "npm ci && npm run build"
rm -f public/hot   # asegura que se usen los assets compilados, no el dev server

echo "==> 5/7 Migrando base de datos y creando admins"
$APP_EXEC php artisan migrate --force
$APP_EXEC php artisan db:seed --force

echo "==> 6/7 Optimizando (rutas y vistas en caché)"
# route:clear antes de route:cache: si quedó una cache compilada previa, el
# comando falla al intentar recachear; limpiarla primero lo evita.
$APP_EXEC php artisan route:clear
$APP_EXEC php artisan view:clear
$APP_EXEC php artisan route:cache
$APP_EXEC php artisan view:cache
# Nota: no cacheamos la config para que puedas editar config/event.php y .env
# y solo reiniciar, sin tener que limpiar caché.

echo "==> 7/7 Listo. Estado de los contenedores:"
$COMPOSE ps
echo ""
echo "✅ Despliegue terminado. La app escucha en 127.0.0.1:\${APP_PORT:-8090}."
echo "   Asegúrate de tener el vhost del host apuntando a ese puerto + HTTPS."
