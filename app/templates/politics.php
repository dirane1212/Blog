<?php ob_start(); ?>
<h1 class="text-3xl font-bold mb-4">Politics</h1>
<p class="text-gray-700">This is the Politics section. (Static placeholder for now.)</p>
<?php $content = ob_get_clean(); render_layout("Politics", $content); ?>
