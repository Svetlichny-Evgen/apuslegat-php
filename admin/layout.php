<?php
function adminHeader(string $title, string $active = ''): void {
    $menu = [
        'index.php'    => 'Дашборд',
        'products.php' => 'Товари',
        'services.php' => 'Послуги',
        'prices.php'   => 'Ціни',
        'backups.php'  => 'Бекапи',
        'settings.php' => 'Налаштування',
    ];
    $flash = getFlash();
    ?><!doctype html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?> — Адмін APUS LEGAT</title>
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<aside class="sidebar">
  <div class="brand">APUS<span>ADMIN</span></div>
  <nav>
    <?php foreach ($menu as $url => $label): ?>
      <a href="<?= e($url) ?>" class="<?= $active === $url ? 'on' : '' ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
    <a href="logout.php" class="logout">Вийти</a>
  </nav>
</aside>
<main class="content">
  <h1><?= e($title) ?></h1>
  <?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['msg']) ?></div>
  <?php endif; ?>
<?php }

function adminFooter(): void {
    ?></main>
</body>
</html><?php }