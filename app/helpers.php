<?php
// app/helpers.php

/**
 * HTML-escape a string (prevents XSS when echoing untrusted content).
 */
function e(?string $v): string {
    return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Build a site URL from a path. Keeps things consistent if base URL changes.
 * Example: url('/politics') -> http://localhost:8000/politics (in dev)
 */
function url(string $path = ''): string {
    $path = '/' . ltrim($path, '/');
    return APP_URL . $path;
}

/**
 * Render a template inside the shared layout.
 * Usage:
 *   ob_start();
 *   ... page content ...
 *   $content = ob_get_clean();
 *   render_layout_page('Title', $content);
 */
function render_layout_page(string $title, string $content): void {
    // layout.php defines render_layout(); load it if not present
    if (!function_exists('render_layout')) {
        require_once __DIR__ . '/templates/layout.php';
    }
    render_layout($title, $content);
}
