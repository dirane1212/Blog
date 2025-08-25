<?php
// app/templates/account.php
require_auth(); // will redirect if not logged in
$u = current_user();

ob_start();
?>
    <h1 class="text-3xl font-bold mb-4">Account</h1>

    <div class="space-y-2">
        <p><span class="font-medium">Email:</span> <?= e($u['email']) ?></p>
        <p><span class="font-medium">Display name:</span> <?= e($u['display_name']) ?></p>
        <p><span class="font-medium">Role:</span> <?= e($u['role']) ?></p>
    </div>

    <form method="post" action="/logout" class="mt-6">
        <?php csrf_field(); ?>
        <button class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Log out</button>
    </form>
<?php
$content = ob_get_clean();
render_layout_page('Account', $content);
