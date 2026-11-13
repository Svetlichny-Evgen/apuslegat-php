<?php
/* Полифиллы для Windows/PHP без mbstring */
if (!function_exists('array_is_list')) {
    function array_is_list(array $a): bool {
        $i = 0;
        foreach ($a as $k => $_) {
            if ($k !== $i++) return false;
        }
        return true;
    }
}
if (!function_exists('mb_strlen')) {
    function mb_strlen($s, $enc = null) { return strlen((string) $s); }
    function mb_substr($s, $start, $len = null, $enc = null) {
        return $len === null ? substr((string) $s, $start) : substr((string) $s, $start, $len);
    }
}

function projectRoot(): string {
    return dirname(__DIR__);
}

function rootPath(string $rel): string {
    return projectRoot() . '/' . ltrim($rel, '/');
}

function loadJson(string $path): array {
    if (!is_file($path)) return [];
    $raw  = file_get_contents($path);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function saveJson(string $path, array $data): bool {
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) return false;
    return file_put_contents($path, $json . "\n", LOCK_EX) !== false;
}

function loadPhpArray(string $path): array {
    if (!is_file($path)) return [];
    if (function_exists('opcache_invalidate')) @opcache_invalidate($path, true);
    $data = @include $path;
    return is_array($data) ? $data : [];
}

function exportPhpValue($v, int $indent = 0): string {
    if (is_array($v)) {
        if (!$v) return '[]';
        $pad    = str_repeat('    ', $indent + 1);
        $endPad = str_repeat('    ', $indent);
        $isList = array_is_list($v);
        $parts  = [];
        foreach ($v as $k => $item) {
            $prefix   = $isList ? '' : var_export($k, true) . ' => ';
            $parts[]  = $pad . $prefix . exportPhpValue($item, $indent + 1);
        }
        return "[\n" . implode(",\n", $parts) . ",\n" . $endPad . ']';
    }
    if (is_string($v))  return var_export($v, true);
    if (is_bool($v))    return $v ? 'true' : 'false';
    if ($v === null)    return 'null';
    if (is_int($v) || is_float($v)) return (string) $v;
    return var_export($v, true);
}

function savePhpArray(string $path, array $data): bool {
    $php = "<?php\nreturn " . exportPhpValue($data) . ";\n";
    $ok  = file_put_contents($path, $php, LOCK_EX) !== false;
    if ($ok && function_exists('opcache_invalidate')) @opcache_invalidate($path, true);
    return $ok;
}

function backupsDir(): string {
    $dir = __DIR__ . '/_backups';
    if (!is_dir($dir)) @mkdir($dir, 0775, true);
    return $dir;
}

function backupFile(string $originalPath): ?string {
    if (!is_file($originalPath)) return null;
    $base = basename($originalPath);
    $ts   = date('Y-m-d_H-i-s');
    $dest = backupsDir() . '/' . $base . '.' . $ts . '.bak';
    return @copy($originalPath, $dest) ? $dest : null;
}

function listBackups(): array {
    $dir = backupsDir();
    $out = [];
    foreach (glob($dir . '/*.bak') ?: [] as $f) {
        $out[] = [
            'file' => basename($f),
            'path' => $f,
            'size' => filesize($f),
            'time' => filemtime($f),
        ];
    }
    usort($out, fn($a, $b) => $b['time'] - $a['time']);
    return $out;
}

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function csrfToken(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}

function csrfCheck(): void {
    $t = $_POST['_csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', (string) $t)) {
        http_response_code(400);
        die('CSRF token mismatch — перезавантажте сторінку');
    }
}

function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function flash(string $msg, string $type = 'ok'): void {
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}

function getFlash(): ?array {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

function productsPath(): string {
    return rootPath('products.json');
}

function findProductRef(array &$inv, string $id): ?array {
    foreach ($inv as $cat => $items) {
        if (!is_array($items)) continue;
        foreach ($items as $i => $item) {
            if (($item['id'] ?? '') === $id) {
                return ['cat' => $cat, 'idx' => $i, 'item' => $item];
            }
        }
    }
    return null;
}