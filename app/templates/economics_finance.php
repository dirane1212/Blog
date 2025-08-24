<?php ob_start(); ?>
<h1 class="text-3xl font-bold mb-4">Economics &amp; Finance</h1>
<p class="text-gray-700">This is the Economics &amp; Finance section. (Static placeholder for now.)</p>
<?php $content = ob_get_clean(); render_layout("Economics & Finance", $content); ?>
