# PPWL Portfolio Application

This repository contains the portfolio application developed for the Advanced Web Programming Practicum (PPWL). The application evolves across the practicum meetings. Work on the source-code tasks for meeting x on branch `pertemuan<x>` and follow the matching module in order, including its final assignment.

## Stack

The current project configuration specifies:

- PHP 8.5 (`.php-version`)
- Laravel 13 (`laravel/framework` `^13.17` in `composer.json`)
- Node.js 24 and npm (`.node-version`, `package.json`)
- SQLite by default; MySQL/MariaDB can be configured as needed

Check the configuration files again when recording versions in a practicum report.

## Run the application

Detailed cross-platform setup and troubleshooting are in [SETUP.md](SETUP.md).
Pick the environment for your platform:

| Platform | Recommended path | Tooling |
|---|---|---|
| NixOS / Linux | **devenv** (this repository) | `PHP 8.5`, `Composer`, `Node 24`, `MariaDB 11` on `127.0.0.1:33060`, Vite |
| Windows 10 (campus lab PCs) | **Herd** | Herd-managed PHP 8.5 + Node 24 + MySQL; `SETUP.md` |
| macOS | **Lerd** (or Herd / devenv) | `.lerd.yaml` in this repo |
| Any OS, no service manager | **Manual** | SQLite default, `SETUP.md` |

### devenv on Linux (NixOS)

The repository ships `devenv.nix`, `devenv.yaml`, and `devenv.lock`. From the
repository root:

```sh
devenv up -d            # MariaDB 33060 + Laravel 8000 + Vite 5173 (background)
devenv shell            # toolchain-only shell for one-off commands
php artisan migrate --seed
composer test
npm run build
devenv down             # stop the services
```

What the environment does (verified 2026-10-29):

- First `devenv shell`/`devenv up` installs `vendor/` and `node_modules/` once (task
  `app:setup`, skipped when both exist).
- The task `app:env-local` creates a gitignored `.env.local` pointing at the
  devenv MariaDB (127.0.0.1:33060, database `ppwl_my_portfolio`). `.env` and its
  `APP_KEY` are never touched; if `.env` is missing it is created from
  `.env.example` first.
- The `laravel` process runs as `APP_ENV=local php artisan serve`, so the app loads
  `.env.local` in both the parent process and the dev-server child (the child
  intentionally drops host env vars). Manual `php artisan serve` in a plain shell
  keeps using `.env` (Lerd/Herd/SQLite) — use `devenv up` inside devenv.
- MariaDB listens on a dedicated port so it never collides with Lerd (3306). The
  database lives in `.devenv/state/mysql` per checkout; `migrate:fresh` only ever
  affects that disposable database.
- The parent PPWL workspace (`../ppwl-laprak/devenv.nix`) imports this file and
  adds the report toolchain (Typst, poppler, imagemagick, editors); use
  `devenv tasks run report:check` there to verify the report stack.

On Windows, the devenv files are inert text: use Herd as described below. On macOS,
use Lerd or Herd; devenv works too if you have Nix installed.

### Lerd on Linux/macOS

The repository includes `.lerd.yaml` for Lerd users. Follow the setup steps in `SETUP.md`. In a linked Lerd environment, the usual setup commands are:

```sh
lerd link
lerd env setup
lerd framework setup
```

The configured PHP and Node versions are 8.5 and 24. Lerd service hostnames such as `lerd-mysql` resolve only inside Lerd.

### Herd on Windows/macOS

Follow the platform-specific instructions in `SETUP.md`. Use PHP 8.5 and Node.js 24. Herd can serve the project through its local domain; `php artisan serve` is optional in that case.

### Manual setup

The default `.env.example` uses SQLite, so a database server is not required. Follow the Manual setup steps in `SETUP.md`, then start the local app and Vite in separate terminals:

```sh
php artisan serve
npm run dev
```

Open <http://localhost:8000>. To use MySQL/MariaDB, create a local database and configure the `DB_*` values in `.env` first.

### Campus lab PCs (Windows 10)

The lab machines use Herd, not Nix. Clone the repository, open it in Herd with
PHP 8.5, run `composer install`, `npm install`, copy `.env.example` to `.env`,
generate the key, and migrate (SQLite default works; Herd's MySQL also works).
Nothing from the devenv setup is required. Keep `node_modules/` and `vendor/` out
of cross-OS copies — reinstall them on the target OS.

## Tests and frontend assets

```sh
composer test
npm run build
```

Run `npm run dev` alongside the PHP server during frontend development to enable Vite's development workflow. Tests use the repository's PHPUnit configuration; check `phpunit.xml` and the test files for their database setup.

## Application areas

- Routes: `routes/web.php`
- Controllers: `app/Http/Controllers/`
- Models: `app/Models/`
- Views: `resources/views/`
- Styles and JavaScript: `resources/css/`, `resources/js/`
- Migrations and seeders: `database/`
