<?php
// app/templates/auth_register.php

$errors = [];
$email = trim($_POST['email'] ?? '');
$name  = trim($_POST['display_name'] ?? '');
$pass  = (string)($_POST['password'] ?? '');
$pass2 = (string)($_POST['password_confirm'] ?? '');

if (is_post()) {
    verify_csrf();

    // Basic validation
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email.';
    if ($name === '') $errors['display_name'] = 'Enter a display name.';
    if (strlen($pass) < 8) $errors['password'] = 'Password must be at least 8 characters.';
    if ($pass !== $pass2) $errors['password_confirm'] = 'Passwords do not match.';

    if (!$errors) {
        try {
            $user = create_user($email, $pass, $name);
            login_user($user);
            flash_set('ok', 'Welcome, your account is ready.');
            $next = $_GET['next'] ?? '/account';
            redirect(safe_next($_GET['next'] ?? null));
        } catch (Throwable $e) {
            // Duplicate email (Postgres unique violation: 23505)
            $errors['email'] = 'That email is already registered.';
        }
    }
}

ob_start();
$ok = flash_get('ok');
?>
    <h1 class="text-3xl font-bold mb-4">Create account</h1>

<?php if ($ok): ?>
    <div class="p-3 bg-green-50 border border-green-200 rounded text-green-800 mb-4"><?= e($ok) ?></div>
<?php endif; ?>

    <form method="post" class="space-y-4 max-w-md">
        <?php csrf_field(); ?>
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input name="email" type="email" value="<?= e($email) ?>" class="mt-1 w-full border rounded p-2" required>
            <?php if (isset($errors['email'])): ?><p class="text-red-600 text-sm mt-1"><?= e($errors['email']) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-medium">Display name</label>
            <input name="display_name" value="<?= e($name) ?>" class="mt-1 w-full border rounded p-2" required>
            <?php if (isset($errors['display_name'])): ?><p class="text-red-600 text-sm mt-1"><?= e($errors['display_name']) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-medium">Password</label>
            <input name="password" type="password" class="mt-1 w-full border rounded p-2" required>
            <?php if (isset($errors['password'])): ?><p class="text-red-600 text-sm mt-1"><?= e($errors['password']) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-medium">Confirm password</label>
            <input name="password_confirm" type="password" class="mt-1 w-full border rounded p-2" required>
            <?php if (isset($errors['password_confirm'])): ?><p class="text-red-600 text-sm mt-1"><?= e($errors['password_confirm']) ?></p><?php endif; ?>
        </div>
        <button class="px-4 py-2 rounded bg-indigo-600 text-white">Create account</button>
        <p class="text-sm mt-2">Already have an account? <a class="underline" href="/login">Sign in</a></p>
    </form>
<?php
$content = ob_get_clean();
render_layout_page('Register', $content);
