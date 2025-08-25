<?php
require_once __DIR__ . '/../posts.php';
$posts = list_published_posts();

ob_start();
?>
    <h1 class="text-3xl font-bold mb-4">Latest Posts</h1>
    <ul class="space-y-4">
        <?php foreach ($posts as $p): ?>
            <li>
                <a href="/post/<?= e($p['slug']) ?>" class="text-xl font-semibold text-indigo-700 hover:underline">
                    <?= e($p['title']) ?>
                </a>
                <p class="text-sm text-gray-600"><?= e($p['excerpt']) ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php
$content = ob_get_clean();
render_layout_page("Home", $content);
