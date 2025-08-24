<?php
ob_start();
?>
    <h1 class="text-2xl font-semibold mb-4">Health Check</h1>
    <p class="text-gray-700">OK at <?= htmlspecialchars(date('Y-m-d H:i:s')) ?></p>
<?php
$content = ob_get_clean();
render_layout("Health", $content);
