<?php
require __DIR__ . '/auth.php';
adminRequireLogin();
require __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('backups.php');
csrfCheck();

$bakName = $_POST['backup'] ?? '';
$bakPath = backupsDir() . '/' . $bakName;
if (!is_file($bakPath)) {
    flash('Бекап не знайдено', 'err');
    redirect('backups.php');
}

/* восстанавливаем в корень / includes/data / includes/lang — ищем оригинал */
$origName = explode('.', $bakName)[0]; // "products.json" или "services.php" и т.д.
$candidates = [
    rootPath($origName),
    rootPath('includes/data/' . $origName),
    rootPath('includes/lang/' . $origName),
];

$dest = null;
foreach ($candidates as $c) {
    if (is_file($c)) { $dest = $c; break; }
}

if (!$dest) {
    /* если файла нет — кладём в корень проекта */
    $dest = rootPath($origName);
}

/* сначала бекапим текущий файл, чтобы можно было откатиться */
backupFile($dest);

if (copy($bakPath, $dest)) {
    if (function_exists('opcache_invalidate')) {
        @opcache_invalidate($dest, true);
    }
    flash('Відновлено з ' . $bakName);
} else {
    flash('Не вдалося відновити', 'err');
}
redirect('backups.php');