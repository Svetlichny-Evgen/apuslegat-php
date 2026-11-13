<?php
require __DIR__ . '/auth.php';
adminRequireLogin();
require __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('services.php');
csrfCheck();

$idx = (int) ($_POST['idx'] ?? -1);
if ($idx < 0) redirect('services.php');

$servicesPath = rootPath('includes/data/services.php');
$services = loadPhpArray($servicesPath);
if (!isset($services[$idx])) {
    flash('Послугу не знайдено', 'err');
    redirect('services.php');
}

backupFile($servicesPath);
array_splice($services, $idx, 1);

if (savePhpArray($servicesPath, $services)) {
    flash('Послугу видалено');
} else {
    flash('Помилка збереження', 'err');
}
redirect('services.php');