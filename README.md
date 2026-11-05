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

Open <http://127.0.0.1:8000>. Use the seeded admin account:

- `admin@example.com` / `password123` (admin)
- `test@example.com` / `password` (non-admin, for 403 checks)

Always use `127.0.0.1`, not `localhost`, so cookies, assets, and redirects stay on
one host.

## Tasks

| Command | Purpose |
|---|---|
| `mise run setup` | Create `.env`, generate key, start DB, install dependencies, migrate, seed, link storage, build assets |
| `mise run dev` | Start DB, Laravel on `127.0.0.1:8000`, and Vite |
| `mise run test` | Run PHPUnit |
| `mise run build` | Build production assets |
| `mise run fresh` | Recreate the local database and seed demo data |
| `mise run check` | Pint, tests, and asset build |
| `mise run db:up` / `db:down` | Start or stop MariaDB |

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
podman-compose -f compose.prod.yaml up -d --build
podman-compose -f compose.prod.yaml run --rm app php artisan migrate --seed --force
```

Open <http://127.0.0.1:8080>. Docker users can replace `podman-compose` with
`docker compose`. The application image is defined in `Dockerfile` and also works
with `podman build`.

For a real deployment, set `APP_URL`, `APP_KEY`, database credentials, and
`SESSION_SECURE_COOKIE=true` in `.env.prod`; do not commit it.

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
- Container files: `Dockerfile`, `compose.yaml`, `compose.prod.yaml`, `docker/`

## Changelog

See [CHANGELOG.md](CHANGELOG.md). Every meeting adds one section.
