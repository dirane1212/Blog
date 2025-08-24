<?php
// app/router.php

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// map URIs to templates
$routes = [
    ''        => __DIR__ . '/templates/home.php',
    'health'  => __DIR__ . '/templates/health.php',
    'politics'          => __DIR__ . '/templates/politics.php',
    'economics-finance' => __DIR__ . '/templates/economics_finance.php',
    'social-affairs'    => __DIR__ . '/templates/social_affairs.php',
    'db-status'         => __DIR__ . '/templates/db_status.php',   // <-- add this
];

if (isset($routes[$uri])) {
    require $routes[$uri];
    exit;
}

require __DIR__ . '/templates/_404.php';
