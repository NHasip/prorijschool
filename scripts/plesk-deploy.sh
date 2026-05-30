#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

echo "==> Starting Laravel deploy in: $ROOT_DIR"

if ! command -v php >/dev/null 2>&1; then
  echo "ERROR: php is not available on PATH."
  exit 1
fi

if ! command -v composer >/dev/null 2>&1; then
  echo "ERROR: composer is not available on PATH."
  exit 1
fi

if [[ ! -f .env && -f .env.example ]]; then
  echo "==> .env not found, copying from .env.example"
  cp .env.example .env
fi

echo "==> Installing PHP dependencies"
composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader

if [[ ! -f .env ]]; then
  echo "ERROR: .env is missing."
  exit 1
fi

if ! grep -q '^APP_KEY=' .env || grep -Eq '^APP_KEY=(\"\"|)$' .env; then
  echo "==> Generating APP_KEY"
  php artisan key:generate --force
fi

echo "==> Ensuring writable directories"
mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache || true

echo "==> Running database migrations"
if [[ "${SKIP_MIGRATIONS:-0}" == "1" ]]; then
  echo "==> SKIP_MIGRATIONS=1 detected, skipping migrations"
else
  php artisan migrate --force
fi

echo "==> Building framework caches"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Creating storage symlink"
php artisan storage:link --force

echo "==> Deployment finished successfully"
