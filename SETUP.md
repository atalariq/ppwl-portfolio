# Setup

This is a Laravel portfolio site. These instructions get it running on Windows, macOS or Linux.

## What you need

- PHP 8.3 or newer
- Composer
- Node.js 20.19 or newer (24 is what the project targets)
- MySQL or MariaDB only if you want to use them. The default SQLite needs no server.

If anything is missing, [Herd](https://herd.laravel.com) on Windows and macOS bundles PHP, Composer and Node. [php.new](https://php.new) installs PHP and Composer on its own.

## Quick start

```sh
git clone <repo-url> ppwl-my-portfolio
cd ppwl-my-portfolio
```

macOS or Linux:

```sh
./setup.sh
```

Windows PowerShell. Use the second line if Windows blocks the script:

```powershell
.\setup.ps1
powershell -ExecutionPolicy Bypass -File .\setup.ps1
```

Windows without a terminal: double-click `setup.cmd`.

The script checks your tools, installs dependencies, creates `.env`, generates the app key, sets up the database and builds the assets. It is safe to run twice.

Then start the app:

```sh
php artisan serve
```

Open <http://localhost:8000>.

While you edit CSS or JS, run this in a second terminal so the browser reloads on change:

```sh
npm run dev
```

## Manual setup

Run these yourself if you want to see each step, or if the script stops partway.

1. `composer install`
2. `cp .env.example .env` (Windows: `copy .env.example .env`)
3. `php artisan key:generate`
4. Create an empty file at `database/database.sqlite`. Skip this if you use MySQL.
5. `php artisan migrate`
6. `npm install`
7. `npm run build`

## Run it

- `php artisan serve`, then open <http://localhost:8000>, or
- In Herd, the project folder is already served at `ppwl-my-portfolio.test`. No `artisan serve` needed.

Pages: `/` home, `/about`, `/education`, `/projects`.

Tests: `composer test`.

## Database

The default is SQLite. Your data lives in `database/database.sqlite`. To start over, delete that file and run `php artisan migrate` again.

To use MySQL instead, change these keys in `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ppwl_my_portfolio
DB_USERNAME=root
DB_PASSWORD=
```

Create the database first, then run `php artisan migrate`. Herd's default MySQL credentials are user `root` with an empty password; create the database from the Herd database panel.

If you copied a `.env` from a machine that runs lerd, then `DB_HOST`, `REDIS_HOST` and `MAIL_HOST` point at `lerd-*` container names. Those only resolve inside lerd. Set them to `127.0.0.1` or start over from `.env.example`.

## Platform notes

### Windows with Herd

Herd serves any registered folder at `<folder>.test` over HTTPS. Add the project folder in Herd, pick PHP 8.3 or newer, then open <https://ppwl-my-portfolio.test>. Node ships with Herd. If `php` is not recognized in a plain terminal, use the terminal inside the Herd app.

### macOS or Linux with lerd

lerd reads `.lerd.yaml`, which asks for PHP 8.5, Node 24 and a MySQL service. Link the folder, wire the environment, then run the framework setup:

```sh
lerd link
lerd env setup
lerd framework setup
```

lerd writes a `.env` that points at `lerd-mysql` and friends. That file is gitignored, so nothing lerd-specific is committed.

### Plain PHP

`php artisan serve` is enough. Make sure the `pdo_sqlite` extension is enabled, which it is by default.

## Troubleshooting

| Symptom | Cause | Fix |
| --- | --- | --- |
| `No application encryption key has been specified` | `.env` missing or `APP_KEY` empty | `php artisan key:generate` |
| `Database file at path [database/database.sqlite] does not exist` | The SQLite file was never created | Create an empty `database/database.sqlite`, then `php artisan migrate` |
| `SQLSTATE[HY000] [2002]` or host `lerd-mysql` not found | `.env` points at hosts that do not exist on this machine | Set `DB_HOST=127.0.0.1` (and `REDIS_HOST`, `MAIL_HOST`), or copy `.env.example` to `.env` again |
| PowerShell says `running scripts is disabled on this system` | Execution policy | `powershell -ExecutionPolicy Bypass -File .\setup.ps1` |
| `Unable to locate file in Vite manifest` | Assets were not built | `npm run dev`, or `npm run build` |
| `Failed to listen on 127.0.0.1:8000` | Port already in use | `php artisan serve --port=8001` |
| `php: command not found` or `'php' is not recognized` | PHP is not on your PATH | Use the Herd terminal, or install PHP through php.new |
| `npm install` complains about the Node version | Node is too old | Install Node 24 |

## Where to edit content

- Routes: `routes/web.php`
- Views: `resources/views/`
- Styles: `resources/css/app.css`
- JavaScript: `resources/js/app.js`
