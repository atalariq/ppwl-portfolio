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

A shared devenv/Flox environment is planned at the parent PPWL workspace level; it is not ready to use yet. See the workspace `TODO.md` for the remaining work. Until then, use Lerd, Herd, or the manual setup above.

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
