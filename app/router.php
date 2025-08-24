<?php
// app/router.php

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// map URIs to templates
$routes = [
    ''        => __DIR__ . '/templates/home.php',
    'health'  => __DIR__ . '/templates/health.php',
];

if (isset($routes[$uri])) {
    require $routes[$uri];
    exit;
}

http_response_code(404);
echo "Page not found";
