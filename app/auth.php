<?php
// app/auth.php
require_once __DIR__ . '/db.php';

/** Return the currently logged-in user (assoc array) or null. */
function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

/** Is someone logged in? */
function is_logged_in(): bool {
    return current_user() !== null;
}

/** Require login for a page; redirect to /login with 'next' return URL. */
function require_auth(): void {
    if (!is_logged_in()) {
        $next = urlencode($_SERVER['REQUEST_URI'] ?? '/account');
        redirect('/login?next=' . $next);
    }
}

/** Log the user in (store minimal info in session and rotate ID). */
function login_user(array $user): void {
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id'           => $user['id'],
        'email'        => $user['email'],
        'display_name' => $user['display_name'],
        'role'         => $user['role'] ?? 'user',
    ];
}

/** Log out and rotate ID. */
function logout_user(): void {
    $_SESSION['user'] = null;
    unset($_SESSION['user']);
    session_regenerate_id(true);
}

/** Look up a user by email. */
function find_user_by_email(string $email): ?array {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $u = $stmt->fetch();
    return $u ?: null;
}

/** Create a user; returns the row or throws on duplicate. */
function create_user(string $email, string $password, string $display_name): array {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $pdo  = db();
    $stmt = $pdo->prepare(
        'INSERT INTO users (email, password_hash, display_name)
         VALUES (:email, :hash, :name)
         RETURNING *'
    );
    return $stmt->execute([
        ':email' => $email,
        ':hash'  => $hash,
        ':name'  => $display_name,
    ]) ? $stmt->fetch() : throw new RuntimeException('Create user failed');
}

/** Attempt to authenticate via email/password; returns true on success. */
function attempt_login(string $email, string $password): bool {
    $user = find_user_by_email($email);
    if (!$user || !$user['is_active']) return false;
    if (!password_verify($password, $user['password_hash'])) return false;

    // Update last_login_at (ignore errors)
    try {
        $pdo = db();
        $stmt = $pdo->prepare('UPDATE users SET last_login_at = now(), updated_at = now() WHERE id = :id');
        $stmt->execute([':id' => $user['id']]);
    } catch (Throwable $e) { /* no-op */ }

    login_user($user);
    return true;
}
