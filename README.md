# PPWL Portfolio Application

Portfolio application for the Advanced Web Programming Practicum (PPWL). The
application grows across meetings; source work for meeting `x` lives on branch
`pertemuan<x>`.

## Stack

- PHP 8.5 + Laravel 13
- Node.js 24 + Vite + Tailwind CSS 4
- MariaDB 11 (Podman/Docker) for local development
- `mise` for tool versions and repeatable tasks

## Quick start (WSL2, Linux, macOS)

Prerequisites: `mise`, Podman (or Docker), and the PHP build dependencies listed
below. On Windows, run these commands inside WSL2.

```sh
git clone https://github.com/atalariq/ppwl-portfolio.git
cd ppwl-portfolio
mise install
mise run setup
mise run dev
```

Open <http://127.0.0.1:8000>. The admin area lives at <http://127.0.0.1:8000/admin>.
Use the seeded accounts:

- `admin` or `admin@example.com` / `password123` (admin)
- `test` or `test@example.com` / `password` (non-admin, for 403 checks)

Always use `127.0.0.1`, not `localhost`, so cookies, assets, and redirects stay on
one host.

## VS Code Dev Container (optional)

The dev container provides the same PHP 8.5, Composer, Node 24, and MariaDB-based
development environment on each machine. It is an alternative to the `mise`
workflow, not a way to sync files between devices.

Requirements: Docker Desktop with Docker Compose and VS Code with the Dev
Containers extension. On Windows, use Docker Desktop's WSL 2 backend; keeping
the repository in the WSL 2 filesystem is recommended for better file
performance.

1. Open the repository folder in VS Code and run **Dev Containers: Reopen in
   Container** from the Command Palette.
2. On first creation, the container installs dependencies, creates `.env`, runs
   migrations and seeders, links storage, and builds assets. Its MariaDB runs
   privately inside Compose and does not publish port 3306 on the host. It
   derives an ignored `.env.devcontainer` from `.env`, leaving the host's `.env`
   unchanged.
3. In separate VS Code terminals, start Laravel and Vite:

   ```sh
   php artisan serve --host=0.0.0.0 --port=8000
   npm run dev -- --host=0.0.0.0
   ```

4. Open <http://127.0.0.1:8000>.

Each device has its own checkout, `.env`, database volume, and uploaded files.
To switch devices without losing changes, commit and push on the current device,
then pull on the next one. A local dev container does not provide live
collaboration; use VS Code Live Share while both devices and the host are online
if you need to edit the same workspace together.

On the host, `mise run` activates the project's pinned tools. For direct PHP
commands outside a `mise` task, use `mise x -- php ...` or activate mise in the
shell with `mise activate`.

## Tasks

| Command | Purpose |
|---|---|
| `mise run setup` | Create `.env`, generate key, start DB, install dependencies, migrate, seed, link storage, build assets |
| `mise run dev` | Start DB, Laravel on `127.0.0.1:8000`, and Vite; uploads allow 8 MB per file and 64 MB per request |
| `mise run test` | Run PHPUnit |
| `mise run build` | Build production assets |
| `mise run fresh` | Recreate the local database and seed demo data |
| `mise run check` | Pint, tests, and asset build |
| `mise run db:up` / `db:down` | Start or stop MariaDB |

## Upload limits

Local `mise run dev` raises PHP's upload limits to 8 MB per file and 64 MB per
request. Validation still caps thumbnails, avatars, and gallery images at 4 MB
per file. The production image ships the same limits in `docker/php.ini` and
`docker/nginx.conf`.

The database runs in Podman/Docker on `127.0.0.1:3306`. The credentials live in
`.env` and match `compose.yaml`.

## Environment files

- `.env.example` is the local template; copy it to `.env`.
- `.env.prod.example` is the production template; copy it to `.env.prod` on the
  server.
- `.env` and `.env.prod` are never committed.

There is no `.env.local` cascade and no second database port. Local development
uses `.env`; production uses `APP_ENV=prod` and `.env.prod`.

## PHP build dependencies

`mise` installs PHP from source through vfox-php. Install the build dependencies
first.

Ubuntu/WSL2:

```sh
sudo apt-get update
sudo apt-get install -y build-essential autoconf bison re2c pkg-config \
  libxml2-dev libssl-dev libicu-dev libzip-dev libonig-dev \
  libcurl4-openssl-dev libpng-dev libjpeg-dev libfreetype6-dev \
  libwebp-dev libgmp-dev libsodium-dev libreadline-dev libbz2-dev
```

macOS:

```sh
xcode-select --install
brew install autoconf bison re2c pkg-config libxml2 openssl@3 icu4c \
  zlib libzip oniguruma freetype jpeg libpng webp gmp libsodium readline bzip2
```

The first `mise install` can take 5–15 minutes because PHP is compiled.

## Full container (deploy or demo)

The full stack runs PHP-FPM + nginx + MariaDB in containers.

```sh
cp .env.prod.example .env.prod
# edit APP_KEY, APP_URL, DB credentials
php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
podman-compose -p ppwl-prod -f compose.prod.yaml up -d --build
podman-compose -p ppwl-prod -f compose.prod.yaml run --rm app php artisan migrate --seed --force
```

Open <http://127.0.0.1:8080>. Docker users can replace `podman-compose` with
`docker compose`. The application image is defined in `Dockerfile` and also works
with `podman build`.

For a real deployment, set `APP_URL`, `APP_KEY`, database credentials, and
`SESSION_SECURE_COOKIE=true` in `.env.prod`; do not commit it.

For a temporary public preview, start the optional Cloudflare Quick Tunnel after
the app stack is running:

```sh
docker compose -p ppwl-prod -f compose.prod.yaml --profile tunnel up -d cloudflared
docker compose -p ppwl-prod -f compose.prod.yaml --profile tunnel logs -f cloudflared
```

Open the `trycloudflare.com` URL printed in the logs. Stop public access with
`docker compose -p ppwl-prod -f compose.prod.yaml --profile tunnel stop cloudflared`.
Use `podman-compose` instead of `docker compose` if using Podman. Quick Tunnels
are temporary and public to anyone with the URL; do not use them for production
or leave demo accounts/passwords enabled. The tunnel service is disabled unless
the `tunnel` profile is explicitly selected.

## Tests and assets

```sh
mise run test
mise run build
```

Tests use in-memory SQLite through `phpunit.xml`; local development uses MariaDB.

## Application areas

- Routes: `routes/web.php`, `routes/auth.php`
- Controllers: `app/Http/Controllers/`
- Models: `app/Models/`
- Views: `resources/views/`
- Design system: `resources/css/app.css`, `resources/views/components/ui/`, `DESIGN.md`
- Migrations and seeders: `database/`
- Container files: `.devcontainer/`, `Dockerfile`, `compose.yaml`, `compose.prod.yaml`, `docker/`

## Changelog

See [CHANGELOG.md](CHANGELOG.md). Every meeting adds one section.
