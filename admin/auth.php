<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function adminConfigPath(): string {
    return __DIR__ . '/config.json';
}

function adminConfig(): array {
    $path = adminConfigPath();
    if (!is_file($path)) {
        $cfg = [
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
            'installed_at'  => date('c'),
        ];
        file_put_contents($path, json_encode($cfg, JSON_PRETTY_PRINT), LOCK_EX);
        return $cfg;
    }
    $raw  = file_get_contents($path);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function adminSaveConfig(array $cfg): bool {
    return file_put_contents(
        adminConfigPath(),
        json_encode($cfg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    ) !== false;
}

function adminIsLoggedIn(): bool {
    return !empty($_SESSION['admin_logged_in']);
}

function adminLogin(string $password): bool {
    $cfg = adminConfig();
    if (empty($cfg['password_hash'])) return false;
    if (!password_verify($password, $cfg['password_hash'])) return false;

    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_login_at']  = time();
    $_SESSION['csrf']            = bin2hex(random_bytes(16));
    return true;
}

function adminLogout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function adminRequireLogin(): void {
    if (!adminIsLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}

function adminChangePassword(string $newPassword): bool {
    if (strlen($newPassword) < 6) return false;
    $cfg = adminConfig();
    $cfg['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
    $cfg['updated_at']    = date('c');
    return adminSaveConfig($cfg);
}