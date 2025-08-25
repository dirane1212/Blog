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

    // Auth
    'register'          => __DIR__ . '/templates/auth_register.php',
    'login'             => __DIR__ . '/templates/auth_login.php',
    'account'           => __DIR__ . '/templates/account.php',
    'logout'            => __DIR__ . '/templates/auth_logout.php',

    // Admin panel
    'admin/post/new'   => __DIR__ . '/templates/admin_post_new.php',

];


// 1) Try static routes first
if (isset($routes[$uri])) {
    require $routes[$uri];
    exit;
}

// 2) Dynamic route: /post/{slug}
if (preg_match('~^post/([a-z0-9_-]+)$~', $uri, $m)) {
    $_GET['slug'] = $m[1];
    require __DIR__ . '/templates/post_view.php';
    exit;
}

// 3) Nothing matched → 404
require __DIR__ . '/templates/_404.php';
