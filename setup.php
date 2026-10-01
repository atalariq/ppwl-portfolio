<?php

declare(strict_types=1);

/**
 * One-command local setup for this project.
 *
 * Runs the same way on macOS, Linux and Windows: call it with `php setup.php`.
 * The `setup.sh` and `setup.ps1` files are thin wrappers around this script.
 *
 * Usage:
 *   php setup.php [--skip-build] [--help]
 *
 * The script is idempotent. Running it twice does not overwrite your `.env`
 * or wipe your database.
 */

$root = __DIR__;
$args = array_slice($argv, 1);

if (in_array('--help', $args, true) || in_array('-h', $args, true)) {
    echo <<<'TXT'
    Portfolio local setup

    Usage: php setup.php [options]

    Options:
      --skip-build   Skip `npm run build` (use when you plan to run `npm run dev`)
      --help, -h     Show this message

    What it does:
      1. Checks PHP, Composer and Node versions
      2. composer install
      3. Creates .env from .env.example if .env is missing
      4. Generates APP_KEY if it is empty
      5. Creates database/database.sqlite when DB_CONNECTION is sqlite
      6. php artisan migrate
      7. npm install
      8. npm run build

    TXT;

    exit(0);
}

$skipBuild = in_array('--skip-build', $args, true);

function out(string $message): void
{
    fwrite(STDOUT, $message.PHP_EOL);
}

function step(string $message): void
{
    out('==> '.$message);
}

function ok(string $message): void
{
    out('    ok: '.$message);
}

function warn(string $message): void
{
    out('    warn: '.$message);
}

function abort(string $message): void
{
    fwrite(STDERR, '    error: '.$message.PHP_EOL);
    out('');
    out('Setup stopped.');
    exit(1);
}

/**
 * Run a command, streaming its output to the console.
 *
 * @return int the exit code
 */
function run(string $command): int
{
    $code = 0;
    passthru($command, $code);

    return (int) $code;
}

/**
 * Run a command and capture its output, used for version probes.
 *
 * @return array{0: int, 1: string}
 */
function capture(string $command): array
{
    $output = [];
    $code = 0;
    exec($command.' 2>&1', $output, $code);

    return [(int) $code, trim(implode(PHP_EOL, $output))];
}

function commandExists(string $binary): bool
{
    $probe = DIRECTORY_SEPARATOR === '\\' ? 'where' : 'command -v';
    [$code] = capture($probe.' '.escapeshellarg($binary));

    return $code === 0;
}

/**
 * Read a dotenv file into a flat array. Repeated keys keep the last value,
 * which is what Symfony's dotenv parser does.
 *
 * @return array<string, string>
 */
function readEnv(string $path): array
{
    $vars = [];

    if (! is_file($path)) {
        return $vars;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#') || ! str_contains($line, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $line, 2);
        $vars[trim($name)] = trim(trim($value), "\"'");
    }

    return $vars;
}

/**
 * Vite 8 supports Node ^20.19.0 or >=22.12.0.
 */
function nodeIsSupported(string $version): bool
{
    if (version_compare($version, '20.19.0', '<')) {
        return false;
    }

    if (version_compare($version, '21.0.0', '<')) {
        return true;
    }

    return version_compare($version, '22.12.0', '>=');
}

if (! function_exists('passthru') || ! function_exists('exec')) {
    abort('This script needs the PHP functions exec() and passthru(). Enable them in php.ini.');
}

out('Portfolio local setup');
out('');

// 1. Make sure we are at the project root.
if (! is_file($root.'/artisan') || ! is_file($root.'/composer.json')) {
    abort('Run this from the project root: artisan and composer.json must be here.');
}

chdir($root);

// 2. Check the tools.
if (version_compare(PHP_VERSION, '8.3.0', '<')) {
    abort('PHP 8.3 or newer is required, found '.PHP_VERSION.'. Install it with Herd (Windows/macOS) or https://php.new');
}
ok('PHP '.PHP_VERSION);

if (! commandExists('composer')) {
    abort('Composer not found. Install it from https://getcomposer.org/download/');
}
ok('Composer available');

[$nodeCode, $nodeRaw] = capture('node --version');

if ($nodeCode !== 0) {
    abort('Node.js not found. Install Node 24 from https://nodejs.org (Herd bundles Node too).');
}

$nodeVersion = ltrim($nodeRaw, 'v');

if (! nodeIsSupported($nodeVersion)) {
    abort('Node.js 20.19 or newer is required, found '.$nodeVersion.'. Install Node 24 from https://nodejs.org');
}
ok('Node '.$nodeVersion);

$php = escapeshellarg(PHP_BINARY);

// 3. PHP dependencies. This must happen before any artisan command.
step('Installing PHP dependencies (composer install)');

if (run('composer install --no-interaction') !== 0) {
    abort('composer install failed. Read the output above for the cause.');
}

// 4. Environment file. Never overwrite an existing one.
step('Preparing .env');
$envPath = $root.'/.env';
$envExample = $root.'/.env.example';

if (is_file($envPath)) {
    ok('.env already exists, left untouched');
} else {
    if (! is_file($envExample)) {
        abort('.env is missing and there is no .env.example to copy from.');
    }

    if (! copy($envExample, $envPath)) {
        abort('Could not create .env from .env.example.');
    }

    ok('Created .env from .env.example');
}

// 5. Application key.
$env = readEnv($envPath);

if (($env['APP_KEY'] ?? '') === '') {
    step('Generating application key');

    if (run($php.' artisan key:generate --no-interaction') !== 0) {
        abort('Could not generate APP_KEY. Run `php artisan key:generate` by hand.');
    }
} else {
    ok('APP_KEY already set');
}

// 6. SQLite database file, only when the app is configured for sqlite.
$env = readEnv($envPath);
$connection = $env['DB_CONNECTION'] ?? 'sqlite';

if ($connection === 'sqlite') {
    $target = $env['DB_DATABASE'] ?? '';

    if ($target === '') {
        $target = 'database/database.sqlite';
    }

    if (! str_starts_with($target, '/') && ! preg_match('#^[A-Za-z]:[\\\\/]#', $target)) {
        $target = $root.'/'.ltrim($target, '/\\');
    }

    if (is_file($target)) {
        ok('SQLite database file exists');
    } elseif (! touch($target)) {
        abort('Could not create '.$target.'. Create the file by hand and re-run.');
    } else {
        ok('Created '.$target);
    }
} else {
    warn("DB_CONNECTION={$connection}. Skipping the SQLite file. Make sure that database is running and the DB_* values in .env are correct.");
}

// 7. Migrations. `migrate`, never `migrate:fresh`: this must not drop data.
step('Running database migrations');

if (run($php.' artisan migrate --force --no-interaction') !== 0) {
    abort('Migrations failed. If you use MySQL or PostgreSQL, check the DB_* values in .env.');
}

// 8. JS dependencies.
step('Installing JS dependencies (npm install)');

if (run('npm install --no-fund --no-audit') !== 0) {
    abort('npm install failed. Check your Node version and network.');
}

// 9. Frontend assets.
if ($skipBuild) {
    warn('Skipped npm run build. Run `npm run build` before browsing, or use `npm run dev`.');
} else {
    step('Building frontend assets (npm run build)');

    if (run('npm run build') !== 0) {
        abort('npm run build failed. Read the output above for the cause.');
    }
}

out('');
out('Setup complete.');
out('');
out('Start the app:');
out('  php artisan serve');
out('  then open http://localhost:8000');
out('');
out('On Herd the folder is served at its .test domain, so `php artisan serve` is not needed.');
out('');
out('While you edit CSS or JS, run this in a second terminal:');
out('  npm run dev');
