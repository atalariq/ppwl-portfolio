# Setup

The supported local flow is `mise` plus Podman/Docker. Herd, Lerd, and devenv are
no longer used.

## 1. Install system prerequisites

- `mise` (<https://mise.jdx.dev>)
- Podman or Docker with Compose
- PHP build dependencies (see the list in `README.md`)

Windows users should work inside WSL2. Native Windows without WSL2 is not a
supported path for this repository.

## 2. Bootstrap

```sh
git clone https://github.com/atalariq/ppwl-portfolio.git
cd ppwl-portfolio
mise install
mise run setup
```

`mise run setup` is idempotent. It creates `.env` from `.env.example` when needed,
generates `APP_KEY` only when missing, starts MariaDB, installs PHP/Node
dependencies, runs migrations and seeders, links `public/storage`, and builds the
frontend.

## 3. Run

```sh
mise run dev
```

Open <http://127.0.0.1:8000>. Use the seeded admin account from `README.md`.

## 4. Stop

```sh
mise run db:down
```

## Production / demo container

```sh
cp .env.prod.example .env.prod
# edit APP_KEY, APP_URL, DB credentials
podman-compose -f compose.prod.yaml up -d --build
podman-compose -f compose.prod.yaml run --rm app php artisan migrate --seed --force
```

Open <http://127.0.0.1:8080>. Replace `podman-compose` with `docker compose` if
using Docker. The app runs PHP-FPM behind nginx; `Dockerfile` is Podman-compatible.

## Troubleshooting

| Symptom | Cause | Fix |
|---|---|---|
| `php: command not found` after `mise install` | PHP build failed | Install the build dependencies in `README.md`, then run `mise install` again |
| Database connection refused | MariaDB container not ready | Run `mise run db:up`, wait for the health check, then `mise run setup` |
| Port 3306 already in use | Another MySQL/MariaDB is running | Stop it, or change `DB_PORT` in `.env` and the compose port mapping together |
| Login redirects back to `/login` | Browser opened a different host or stale cookie | Use `http://127.0.0.1:8000` consistently; clear cookies for the site |
| Images return 404 | `public/storage` missing | `php artisan storage:link` (run by `mise run setup`) |
| Vite manifest missing | Assets not built | `mise run build` or run `mise run dev` |
| CSS changes trigger full reload | Vite HMR reconnecting | Ensure Vite uses `127.0.0.1:5173` and the browser uses `127.0.0.1:8000` |
| `APP_KEY` missing | `.env` incomplete | `php artisan key:generate` |
| Permission denied on storage | Container/host UID mismatch | Fix ownership of `storage/` and `bootstrap/cache/` |

## Manual commands

If you prefer not to use the task runner:

```sh
cp .env.example .env
php artisan key:generate
podman-compose -f compose.yaml up -d db
composer install
php artisan migrate --seed
php artisan storage:link
npm ci
npm run build
php artisan serve --host=127.0.0.1 --port=8000 --no-reload
```
