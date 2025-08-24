<?php ob_start(); ?>
<h1 class="text-2xl font-semibold mb-2">404 — Page not found</h1>
<p class="text-gray-700">We couldn’t find that page. Try the navigation above.</p>
<?php $content = ob_get_clean(); render_layout("Not found", $content); ?>
