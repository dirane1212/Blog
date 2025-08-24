<?php
require_once __DIR__ . '/../db.php';

ob_start();
$ok = true;
$error = null;
$count = null;
$current = null;

try {
    $pdo = db();
    $res = $pdo->query("SELECT COUNT(*) AS c FROM app_heartbeat");
    $row = $res->fetch();
    $count = (int)$row['c'];

    $res2 = $pdo->query("SELECT now() AT TIME ZONE 'Africa/Mogadishu' AS mogadishu_now");
    $current = $res2->fetch()['mogadishu_now'];
} catch (Throwable $e) {
    $ok = false;
    $error = $e->getMessage();
}
?>
    <h1 class="text-2xl font-semibold mb-4">Database Status</h1>
<?php if ($ok): ?>
    <div class="p-4 bg-green-50 border border-green-200 rounded">
        <p class="text-green-800">✅ Connected. Heartbeat rows: <span class="font-mono"><?= e((string)$count) ?></span></p>
        <p class="text-green-800 mt-2">Server time (Mogadishu): <span class="font-mono"><?= e((string)$current) ?></span></p>
    </div>
<?php else: ?>
    <div class="p-4 bg-red-50 border border-red-200 rounded">
        <p class="text-red-800">❌ DB error: <span class="font-mono"><?= e($error) ?></span></p>
    </div>
<?php endif; ?>
<?php
$content = ob_get_clean();
render_layout_page("DB Status", $content);
