<?php
// app/config.php
// Minimal .env reader and app config (no external libraries).

function _load_env(string $path): void {
    if (!is_file($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2));
        // Remove surrounding quotes if present
        if (str_starts_with($v, '"') && str_ends_with($v, '"')) $v = substr($v, 1, -1);
        if (str_starts_with($v, "'") && str_ends_with($v, "'")) $v = substr($v, 1, -1);
        $_ENV[$k] = $v;
        putenv("$k=$v");
    }
}

_load_env(__DIR__ . '/../.env'); // load local env (if present)

// Core config (read once)
define('APP_ENV', getenv('APP_ENV') ?: 'dev');
define('APP_URL', rtrim(getenv('APP_URL') ?: 'http://localhost:8000', '/'));
define('APP_TIMEZONE', getenv('TIMEZONE') ?: 'UTC');

// Set timezone globally
date_default_timezone_set(APP_TIMEZONE);
