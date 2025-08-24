<?php
// app/db.php
require_once __DIR__ . '/config.php';

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $dsn  = getenv('DB_DSN') ?: '';
    $user = getenv('DB_USER') ?: '';
    $pass = getenv('DB_PASS') ?: '';

    if ($dsn === '' || $user === '') {
        throw new RuntimeException('Database environment variables are missing.');
    }

    $pdo = new PDO(
        $dsn,
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    // Ensure UTC storage by default (we’ll format in app TZ)
    $pdo->exec("SET TIME ZONE 'UTC'");
    return $pdo;
}
