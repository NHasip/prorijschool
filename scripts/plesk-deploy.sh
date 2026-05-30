#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

echo "==> Starting Laravel deploy in: $ROOT_DIR"

PHP_BIN="${PHP_BIN:-}"
if [[ -z "$PHP_BIN" ]]; then
  if command -v php >/dev/null 2>&1; then
    PHP_BIN="$(command -v php)"
  else
    for candidate in \
      /opt/plesk/php/8.4/bin/php \
      /opt/plesk/php/8.3/bin/php \
      /opt/plesk/php/8.2/bin/php \
      /opt/plesk/php/8.1/bin/php; do
      if [[ -x "$candidate" ]]; then
        PHP_BIN="$candidate"
        break
      fi
    done
  fi
fi

if [[ -z "$PHP_BIN" || ! -x "$PHP_BIN" ]]; then
  echo "ERROR: PHP binary not found. Set PHP_BIN manually."
  exit 1
fi

COMPOSER_CMD=()
if command -v composer >/dev/null 2>&1; then
  COMPOSER_CMD=("$(command -v composer)")
elif [[ -f /usr/lib/plesk-9.0/composer.phar ]]; then
  COMPOSER_CMD=("$PHP_BIN" "/usr/lib/plesk-9.0/composer.phar")
else
  echo "ERROR: Composer not found (composer command or /usr/lib/plesk-9.0/composer.phar)."
  exit 1
fi

echo "==> Using PHP binary: $PHP_BIN"
"$PHP_BIN" -v | head -n 1
echo "==> Using Composer command: ${COMPOSER_CMD[*]}"

if [[ ! -f .env && -f .env.example ]]; then
  echo "==> .env not found, copying from .env.example"
  cp .env.example .env
fi

echo "==> Installing PHP dependencies"
"${COMPOSER_CMD[@]}" install --no-interaction --prefer-dist --no-dev --optimize-autoloader

if [[ ! -f .env ]]; then
  echo "ERROR: .env is missing."
  exit 1
fi

if ! grep -q '^APP_KEY=' .env || grep -Eq '^APP_KEY=(\"\"|)$' .env; then
  echo "==> Generating APP_KEY"
  "$PHP_BIN" artisan key:generate --force
fi

echo "==> Ensuring writable directories"
mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache || true

echo "==> Running database migrations"
if [[ "${SKIP_MIGRATIONS:-0}" == "1" ]]; then
  echo "==> SKIP_MIGRATIONS=1 detected, skipping migrations"
else
  "$PHP_BIN" artisan migrate --force
fi

echo "==> Building framework caches"
"$PHP_BIN" artisan optimize:clear
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache

echo "==> Creating storage symlink"
"$PHP_BIN" artisan storage:link --force

echo "==> Deployment finished successfully"
