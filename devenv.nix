# Development environment for the PPWL portfolio application.
#
#   devenv up        # MariaDB (127.0.0.1:33060) + Laravel (8000) + Vite (5173)
#   devenv shell     # interactive shell with the toolchain only
#
# The PPWL workspace root imports this file and adds the report toolchain
# (Typst, poppler, imagemagick, editors), so configuration lives here once.
{ pkgs, config, ... }:

{
  # The app owns its .env; do not let devenv load it into the shell (it would
  # fight with the DB_* overrides below and with phpunit's testing env).
  dotenv.disableHint = true;

  languages.php = {
    enable = true;
    version = "8.5";
    # The generic pkgs.composer currently fails to build on nixpkgs-unstable
    # (php ini double-load warning pollutes the build hook); the php85-bound
    # composer builds cleanly and keeps Composer on the same PHP major.
    packages.composer = pkgs.php85Packages.composer;
    extensions = [
      "bcmath"
      "ctype"
      "curl"
      "dom"
      "exif"
      "fileinfo"
      "filter"
      "gd"
      "intl"
      "mbstring"
      "openssl"
      "pdo"
      "pdo_mysql"
      "pdo_sqlite"
      "session"
      "tokenizer"
      "zip"
    ];
    ini = ''
      memory_limit = 512M
    '';
  };

  languages.javascript = {
    enable = true;
    package = pkgs.nodejs_24;
    npm.enable = true;
  };

  # MariaDB on a dedicated port so it never collides with Lerd's MySQL (3306)
  # or a system MySQL. Data lives in .devenv/state/mysql per checkout.
  services.mysql = {
    enable = true;
    package = pkgs.mariadb;
    initialDatabases = [{ name = "ppwl_my_portfolio"; }];
    settings.mysqld.port = 33060;
  };

  # Point CLI commands at the devenv MariaDB WITHOUT touching .env: real
  # environment values beat .env values in Laravel. Only these four keys are
  # exported, so phpunit.xml still forces in-memory SQLite for tests.
  # APP_ENV is deliberately NOT exported: it would leak into $_SERVER and
  # override phpunit's `testing` environment (breaking the CSRF test bypass).
  env = {
    DB_HOST = "127.0.0.1";
    DB_PORT = "33060";
    DB_USERNAME = "root";
    DB_PASSWORD = "";
  };

  processes = {
    # APP_ENV=local makes the app load the generated `.env.local` (full
    # devenv database block); it is on `php artisan serve`'s passthrough
    # list, so the dev-server child process loads it too.
    laravel.exec = "APP_ENV=local php artisan serve --host=127.0.0.1 --port=8000";
    vite.exec = "npm run dev";
  };

  # Install dependencies once per checkout (skipped when vendor/ and
  # node_modules/ already exist). Works from the repo itself and from the
  # PPWL workspace, where this file is imported by the root devenv.
  tasks."app:setup" = {
    exec = ''
      set -eu
      cd "$DEVENV_ROOT"
      [ -f composer.json ] || cd code
      composer install --no-interaction --no-progress
      npm install
    '';
    status = ''
      cd "$DEVENV_ROOT" || exit 1
      [ -f composer.json ] || cd code
      [ -d vendor ] && [ -x node_modules/.bin/vite ]
    '';
    before = [ "devenv:enterShell" ];
  };

  # One-time, idempotent: create `.env.local` pointing at the devenv MariaDB.
  # Never overwrites an existing `.env`, `.env.local`, or APP_KEY.
  tasks."app:env-local" = {
    exec = ''
      set -eu
      cd "$DEVENV_ROOT"
      [ -f composer.json ] || cd code
      [ -f .env ] || cp .env.example .env
      if [ ! -f .env.local ]; then
        cp .env .env.local
        sed -i -E \
          's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/; s/^DB_HOST=.*/DB_HOST=127.0.0.1/; s/^DB_PORT=.*/DB_PORT=33060/; s/^DB_USERNAME=.*/DB_USERNAME=root/; s/^DB_PASSWORD=.*/DB_PASSWORD=/; s/^DB_DATABASE=.*/DB_DATABASE=ppwl_my_portfolio/' \
          .env.local
        echo "app:env-local: created .env.local (devenv MariaDB at 127.0.0.1:33060)"
      fi
      if ! grep -qE '^APP_KEY=.+' .env.local; then
        php artisan key:generate --force --env=local
      fi
    '';
    status = ''
      cd "$DEVENV_ROOT" || exit 1
      [ -f composer.json ] || cd code
      [ -f .env ] && [ -f .env.local ] && grep -qE '^APP_KEY=.+' .env.local
    '';
    after = [ "app:setup" ];
    before = [ "devenv:enterShell" ];
  };
}
