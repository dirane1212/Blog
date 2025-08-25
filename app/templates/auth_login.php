<?php
// app/templates/auth_login.php
if (is_logged_in()) {
    redirect(safe_next($_GET['next'] ?? null));
}


$errors = [];
$email = trim($_POST['email'] ?? '');
$pass  = (string)($_POST['password'] ?? '');



if (is_post()) {
    verify_csrf();

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email.';
    if ($pass === '') $errors['password'] = 'Enter your password.';

    if (!$errors) {
        if (attempt_login($email, $pass)) {
            flash_set('ok', 'Welcome back!');
            $next = $_GET['next'] ?? '/account';
            redirect(safe_next($_GET['next'] ?? null));
        } else {
            $errors['form'] = 'Invalid credentials or inactive account.';
        }
    }
}

ob_start();
$ok = flash_get('ok');
?>
    <h1 class="text-3xl font-bold mb-4">Sign in</h1>

<?php if ($ok): ?>
    <div class="p-3 bg-green-50 border border-green-200 rounded text-green-800 mb-4"><?= e($ok) ?></div>
<?php endif; ?>

<?php if (isset($errors['form'])): ?>
    <div class="p-3 bg-red-50 border border-red-200 rounded text-red-800 mb-4"><?= e($errors['form']) ?></div>
<?php endif; ?>

    <form method="post" class="space-y-4 max-w-md">
        <?php csrf_field(); ?>
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input name="email" type="email" value="<?= e($email) ?>" class="mt-1 w-full border rounded p-2" required>
            <?php if (isset($errors['email'])): ?><p class="text-red-600 text-sm mt-1"><?= e($errors['email']) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-medium">Password</label>
            <input name="password" type="password" class="mt-1 w-full border rounded p-2" required>
            <?php if (isset($errors['password'])): ?><p class="text-red-600 text-sm mt-1"><?= e($errors['password']) ?></p><?php endif; ?>
        </div>
        <button class="px-4 py-2 rounded bg-indigo-600 text-white">Sign in</button>
        <p class="text-sm mt-2">No account? <a class="underline" href="/register">Create one</a></p>
    </form>
<?php
$content = ob_get_clean();
render_layout_page('Login', $content);
