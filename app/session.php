<?php
// app/session.php
// Starts a secure-enough session for dev and prod.

function start_session_if_needed(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;

    $secure = (APP_ENV !== 'dev'); // on HTTPS in prod, secure cookies
    session_set_cookie_params([
        'lifetime' => 0,          // session cookie
        'path'     => '/',
        'domain'   => '',         // default
        'secure'   => $secure,    // true on HTTPS, false in dev http://localhost
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('BLOGSESSID');
    session_start();

    // Rotate ID on first use to defend against fixation
    if (empty($_SESSION['_init'])) {
        session_regenerate_id(true);
        $_SESSION['_init'] = true;
    }
}
