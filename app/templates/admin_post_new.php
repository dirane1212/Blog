<?php
// app/templates/admin_post_new.php

require_auth();
$u = current_user();
if (!$u || !in_array($u['role'], ['author','editor','admin'], true)) {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

require_once __DIR__ . '/../posts.php';

$ALLOWED_SECTIONS = ['politics','economics_finance','social_affairs'];
$ALLOWED_STATUS   = ['draft','published'];
$ALLOWED_ACCESS   = ['free','premium'];

function slugify_basic(string $s): string {
    $s = strtolower($s);
    $s = preg_replace('~[^a-z0-9]+~', '-', $s) ?? '';
    $s = trim($s, '-');
    $s = preg_replace('~-+~', '-', $s) ?? '';
    return $s !== '' ? $s : 'post';
}

$errors  = [];
$title   = trim($_POST['title'] ?? '');
$slug    = trim($_POST['slug'] ?? '');
$section = $_POST['section'] ?? 'politics';
$excerpt = trim($_POST['excerpt'] ?? '');
$body_md = $_POST['body_md'] ?? '';
$status  = $_POST['status'] ?? 'draft';
$access  = $_POST['access_level'] ?? 'free';

if (is_post()) {
    verify_csrf();

    // Normalization
    if ($slug === '' && $title !== '') {
        $slug = slugify_basic($title);
    }

    // Validation
    if ($title === '')               { $errors['title'] = 'Title required.'; }
    if ($slug === '')                { $errors['slug']  = 'Slug required.'; }
    if (!preg_match('~^[a-z0-9_-]+$~', $slug)) {
        $errors['slug'] = 'Slug can contain a–z, 0–9, _ and - only.';
    }
    if (!in_array($section, $ALLOWED_SECTIONS, true)) {
        $errors['section'] = 'Invalid section.';
    }
    if (!in_array($status, $ALLOWED_STATUS, true)) {
        $errors['status'] = 'Invalid status.';
    }
    if (!in_array($access, $ALLOWED_ACCESS, true)) {
        $errors['access_level'] = 'Invalid access level.';
    }

    if (!$errors) {
        try {
            $p = save_post([
                'author_id'    => (int)$u['id'],
                'title'        => $title,
                'slug'         => $slug,
                'section'      => $section,
                'excerpt'      => $excerpt,
                'body_md'      => $body_md,
                'access_level' => $access,
                'status'       => $status,
            ]);

            if (($p['status'] ?? '') === 'published') {
                flash_set('ok', 'Post published.');
                redirect('/post/' . $p['slug']);
            } else {
                flash_set('ok', 'Draft saved. It’s not public yet.');
                redirect('/admin/post/new');
            }
        } catch (Throwable $e) {
            // Unique slug violation (Postgres 23505) → user-friendly error
            if (method_exists($e, 'getCode') && $e->getCode() === '23505') {
                $errors['slug'] = 'Slug already exists. Try another.';
            } else {
                $errors['form'] = $e->getMessage();
            }
        }
    }
}

$ok = flash_get('ok');

ob_start();
?>
    <h1 class="text-2xl font-bold mb-4">New Post</h1>

<?php if ($ok): ?>
    <div class="p-3 mb-4 bg-green-50 border border-green-200 text-green-800 rounded">
        <?= e($ok) ?>
    </div>
<?php endif; ?>

<?php if (isset($errors['form'])): ?>
    <div class="p-3 mb-4 bg-red-50 border border-red-200 text-red-800 rounded">
        <?= e($errors['form']) ?>
    </div>
<?php endif; ?>

    <form method="post" class="space-y-4 max-w-2xl">
        <?php csrf_field(); ?>

        <div>
            <label class="block text-sm font-medium">Title</label>
            <input name="title" value="<?= e($title) ?>" class="mt-1 border rounded p-2 w-full" required>
            <?php if (isset($errors['title'])): ?><p class="text-red-600 text-sm mt-1"><?= e($errors['title']) ?></p><?php endif; ?>
        </div>

        <div>
            <label class="block text-sm font-medium">Slug</label>
            <input name="slug" value="<?= e($slug) ?>" class="mt-1 border rounded p-2 w-full" placeholder="auto-filled from title if blank">
            <?php if (isset($errors['slug'])): ?><p class="text-red-600 text-sm mt-1"><?= e($errors['slug']) ?></p><?php endif; ?>
        </div>

        <div>
            <label class="block text-sm font-medium">Section</label>
            <select name="section" class="mt-1 border rounded p-2 w-full">
                <option value="politics"           <?= $section==='politics' ? 'selected' : '' ?>>Politics</option>
                <option value="economics_finance"  <?= $section==='economics_finance' ? 'selected' : '' ?>>Economics &amp; Finance</option>
                <option value="social_affairs"     <?= $section==='social_affairs' ? 'selected' : '' ?>>Social Affairs</option>
            </select>
            <?php if (isset($errors['section'])): ?><p class="text-red-600 text-sm mt-1"><?= e($errors['section']) ?></p><?php endif; ?>
        </div>

        <div>
            <label class="block text-sm font-medium">Excerpt</label>
            <textarea name="excerpt" class="mt-1 border rounded p-2 w-full" rows="3"><?= e($excerpt) ?></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium">Body (Markdown)</label>
            <textarea name="body_md" rows="10" class="mt-1 border rounded p-2 w-full"><?= e($body_md) ?></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="mt-1 border rounded p-2 w-full">
                    <option value="draft"     <?= $status==='draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= $status==='published' ? 'selected' : '' ?>>Published</option>
                </select>
                <?php if (isset($errors['status'])): ?><p class="text-red-600 text-sm mt-1"><?= e($errors['status']) ?></p><?php endif; ?>
            </div>
            <div>
                <label class="block text-sm font-medium">Access Level</label>
                <select name="access_level" class="mt-1 border rounded p-2 w-full">
                    <option value="free"    <?= $access==='free' ? 'selected' : '' ?>>Free</option>
                    <option value="premium" <?= $access==='premium' ? 'selected' : '' ?>>Premium</option>
                </select>
                <?php if (isset($errors['access_level'])): ?><p class="text-red-600 text-sm mt-1"><?= e($errors['access_level']) ?></p><?php endif; ?>
            </div>
        </div>

        <button class="bg-indigo-600 text-white px-4 py-2 rounded">Save</button>
    </form>
<?php
$content = ob_get_clean();
// Use whichever you have available; render_layout is always defined
render_layout('New Post', $content);
// If you prefer the wrapper and have it defined, the old call also works:
// render_layout_page('New Post', $content);
