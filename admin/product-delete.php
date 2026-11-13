<?php
require __DIR__ . '/auth.php';
adminRequireLogin();
require __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('products.php');
}
csrfCheck();

$id = trim($_POST['id'] ?? '');
if ($id === '') redirect('products.php');

$inventory = loadJson(productsPath());
$inv = &$inventory['inventory'];

$ref = findProductRef($inv, $id);
if ($ref) {
    backupFile(productsPath());
    array_splice($inv[$ref['cat']], $ref['idx'], 1);
    if (saveJson(productsPath(), $inventory)) {
        flash('Товар видалено');
    } else {
        flash('Помилка збереження', 'err');
    }
}
redirect('products.php');