<?php
// Boot order: config → session → helpers → auth → layout → router
require __DIR__ . '/../app/config.php';

if (APP_ENV === 'dev') {
    ini_set('display_errors','1');
    ini_set('display_startup_errors','1');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
}
require __DIR__ . '/../app/session.php';
start_session_if_needed();

require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/auth.php';
require __DIR__ . '/../app/templates/layout.php';
require __DIR__ . '/../app/router.php';
