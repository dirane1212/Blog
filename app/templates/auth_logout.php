<?php
// app/templates/auth_logout.php
if (is_post()) {
    verify_csrf();
    logout_user();
    flash_set('ok', 'You have been logged out.');
    redirect('/');
}
http_response_code(405);
echo 'Method Not Allowed';
