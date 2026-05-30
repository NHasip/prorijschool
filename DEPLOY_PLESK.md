# Plesk Deploy Setup

Use this for `prorijschool.necmardemo.nl`.

## 1. Document root

In Plesk set the domain document root to Laravel's `public` folder.

Example:
- If repo is in `httpdocs`: document root `httpdocs/public`

## 2. Additional deployment actions

In Plesk Git deployment, set this command:

```bash
bash scripts/plesk-deploy.sh
```

If you use Scheduled Tasks (without SSH), run:

```bash
/bin/bash -lc 'cd ~/prorijschool.necmardemo.nl && bash scripts/plesk-deploy.sh > deploy.log 2>&1'
```

Then inspect `deploy.log` in File Manager.

## 3. Required production `.env` values

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://prorijschool.necmardemo.nl
```

Set your own database and mail settings in `.env`.

## 4. Optional: skip migrations for a deploy

If needed once:

```bash
SKIP_MIGRATIONS=1 bash scripts/plesk-deploy.sh
```
