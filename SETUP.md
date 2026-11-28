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

Open <http://127.0.0.1:8000>. The admin area is at <http://127.0.0.1:8000/admin>.
Use the seeded admin account from `README.md` (`admin` / `password123`).

## 4. Stop

```sh
mise run db:down
```

## Optional: VS Code Dev Container

Install Docker Desktop with Compose and the VS Code Dev Containers extension.
On Windows, enable Docker Desktop's WSL 2 backend; keeping the repository in
the WSL 2 filesystem is recommended for better file performance. Open the
repository in VS Code and choose **Dev Containers: Reopen in Container**. The
first container creation installs Composer and npm dependencies, creates `.env`
if needed, derives the ignored `.env.devcontainer` for the container without
changing host `.env`, migrates and seeds MariaDB, links storage, and builds
assets. MariaDB is private to the Compose network, so it does not need host port
3306 and will not conflict with `mise run dev`.

Start the application in separate terminals inside the container:

```sh
php artisan serve --host=0.0.0.0 --port=8000
npm run dev -- --host=0.0.0.0
```

Open <http://127.0.0.1:8000>. When switching from the lab PC to the laptop, push
your Git changes before leaving and pull them on the other device. The container,
database, `.env`, and uploaded files are local to each machine; this does not
provide live collaboration. Use VS Code Live Share if you need both devices to
edit one active workspace at the same time.

The Dev Container is optional; the `mise` workflow above remains supported. For
direct PHP commands on the host, use `mise x -- php ...` or activate mise with
`mise activate`.

## Production / demo container

```sh
cp .env.prod.example .env.prod
# edit APP_KEY, APP_URL, DB credentials
podman-compose -p ppwl-prod -f compose.prod.yaml up -d --build
podman-compose -p ppwl-prod -f compose.prod.yaml run --rm app php artisan migrate --seed --force
```

Open <http://127.0.0.1:8080>. Replace `podman-compose` with `docker compose` if
using Docker. The app runs PHP-FPM behind nginx; `Dockerfile` is Podman-compatible.

To make a temporary public preview, start the optional Cloudflare Quick Tunnel
after the app stack is running:

```sh
docker compose -p ppwl-prod -f compose.prod.yaml --profile tunnel up -d cloudflared
docker compose -p ppwl-prod -f compose.prod.yaml --profile tunnel logs -f cloudflared
```

Open the `trycloudflare.com` URL in the logs. Stop the tunnel with
`docker compose -p ppwl-prod -f compose.prod.yaml --profile tunnel stop cloudflared`.
Replace `docker compose` with `podman-compose` when using Podman. Anyone with
the URL can access the site, so use this only for temporary previews and disable
or change demo credentials first. The tunnel stays off unless the `tunnel`
profile is explicitly enabled.

## Upload limits

`mise run dev` starts PHP with `upload_max_filesize=8M` and
`post_max_size=64M`. The production image mirrors this through `docker/php.ini`
and `docker/nginx.conf`. Application validation still limits each thumbnail,
avatar, and gallery image to 4 MB.

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
php -d upload_max_filesize=8M -d post_max_size=64M artisan serve --host=127.0.0.1 --port=8000 --no-reload
```
