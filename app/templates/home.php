<?php
ob_start();
?>
    <h1 class="text-3xl font-bold mb-4">Welcome to My Blog</h1>
    <p class="text-gray-700">This is the home page served through our router and layout system.</p>

    <p class="mt-6 text-sm text-gray-500">
        Environment: <span class="font-mono"><?= e(APP_ENV) ?></span> ·
        Base URL: <a href="<?= e(APP_URL) ?>" class="underline"><?= e(APP_URL) ?></a>
    </p>


<?php
$content = ob_get_clean();
render_layout_page("Home", $content);

