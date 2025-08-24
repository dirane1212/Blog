<?php
// Boot order matters: config -> helpers -> layout -> router
require __DIR__ . '/../app/config.php';
require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/templates/layout.php';
require __DIR__ . '/../app/router.php';
