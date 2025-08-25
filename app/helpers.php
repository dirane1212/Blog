<?php
// app/helpers.php

/**
 * HTML-escape a string (prevents XSS when echoing untrusted content).
 */
function e(?string $v): string {
    return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Build an absolute site URL from a path.
 * Example: url('/login') -> http://localhost:8000/login (in dev)
 */
function url(string $path = ''): string {
    $path = '/' . ltrim($path, '/');
    return APP_URL . $path;
}

/** Redirect to a path or absolute URL and exit. */
function redirect(string $to): never {
    // allow only same-site paths
    if ($to === '' || $to[0] !== '/') {
        $to = '/';
    }
    $path = ($to !== '' && $to[0] === '/') ? $to : '/';
    header('Location: ' . url($path), true, is_post() ? 303 : 302);
    exit;

    // Only allow in-site paths. Anything else goes to "/".


}

/** Is the current request a POST? */
function is_post(): bool {
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

/** CSRF: get or create a token stored in session */
function csrf_token(): string {
    if (!isset($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

/** CSRF: echo hidden input for forms */
function csrf_field(): void {
    echo '<input type="hidden" name="_csrf" value="'.e(csrf_token()).'">';
}

/** CSRF: verify incoming POST token (throws on failure) */
function verify_csrf(): void {
    $sent = $_POST['_csrf'] ?? '';
    if (!is_string($sent) || !hash_equals($_SESSION['_csrf'] ?? '', $sent)) {
        http_response_code(419); // authentication timeout / invalid csrf
        echo 'Invalid CSRF token.';
        exit;
    }
}

/** Flash messages (one-time display) */
function flash_set(string $key, string $message): void {
    $_SESSION['_flash'][$key] = $message;
}

function flash_get(string $key): ?string {
    if (!isset($_SESSION['_flash'][$key])) return null;
    $msg = $_SESSION['_flash'][$key];
    unset($_SESSION['_flash'][$key]);
    return $msg;
}

function render_layout_page(string $title, string $content): void {
    if (!function_exists('render_layout')) {
        require_once __DIR__ . '/templates/layout.php';
    }
    render_layout($title, $content);
}

function safe_next(?string $next, string $fallback = '/account'): string {
    // Only allow same-site paths like "/account" or "/politics"
    return (is_string($next) && $next !== '' && $next[0] === '/') ? $next : $fallback;
}