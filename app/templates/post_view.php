<?php
require_once __DIR__ . '/../posts.php';
$slug = $_GET['slug'] ?? '';
$p = $slug ? find_post_by_slug($slug) : null;

if (!$p) {
    require __DIR__ . '/_404.php';
    exit;
}

ob_start();
?>
    <h1 class="text-3xl font-bold mb-4"><?= e($p['title']) ?></h1>
    <div class="prose"><?= $p['body_html'] ?></div>
<?php
$content = ob_get_clean();
render_layout_page($p['title'], $content);
