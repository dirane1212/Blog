<?php
ob_start();
?>
    <h1 class="text-3xl font-bold mb-4">Welcome to My Blog</h1>
    <p class="text-gray-700">This is the home page served through our router and layout system.</p>
<?php
$content = ob_get_clean();
render_layout("Home", $content);
