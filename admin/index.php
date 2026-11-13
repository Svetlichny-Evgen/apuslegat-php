<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/helpers.php';   // <-- Подключаем хелперы (там лежит redirect()) сразу!

/* ===== Логин ===== */
if (!adminIsLoggedIn()) {
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (adminLogin($_POST['password'] ?? '')) {
            redirect('index.php');
        }
        $error = 'Невірний пароль';
    }
    ?><!doctype html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Вхід — Адмін APUS LEGAT</title>
  <link rel="stylesheet" href="<?= htmlspecialchars(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/', ENT_QUOTES) ?>assets/admin.css">
</head>
<body>
<form method="POST" class="login-wrap" autocomplete="off">
  <div class="login-box">
    <h1>APUS<span style="color:#e60c0c">ADMIN</span></h1>
    <p class="subtitle">Панель управління</p>
    <?php if ($error): ?><div class="alert alert-err"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <div class="form-row">
      <label>Пароль</label>
      <input type="password" name="password" autofocus required>
    </div>
    <div class="btn-bar" style="border:none;padding:0;margin-top:0">
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
        Увійти
      </button>
    </div>
    <p class="hint-pwd">За замовчуванням: <code>admin123</code></p>
  </div>
</form>
</body>
</html><?php
    exit;
}

/* ===== Дашборд ===== */
require __DIR__ . '/layout.php';

$products  = loadJson(rootPath('products.json'));
$invCount  = 0;
foreach (($products['inventory'] ?? []) as $items) {
    if (is_array($items)) $invCount += count($items);
}

$services  = loadPhpArray(rootPath('includes/data/services.php'));
$svcCount  = count($services);
$priceSecs = glob(rootPath('includes/data/*.php')) ?: [];
$backups   = listBackups();

adminHeader('Дашборд', 'index.php');
?>
<div class="cards">
  <a href="products.php" class="card">
    <h2><?= (int) $invCount ?></h2>
    <p>Товарів у каталозі</p>
  </a>
  <a href="services.php" class="card">
    <h2><?= (int) $svcCount ?></h2>
    <p>Послуг у Hub</p>
  </a>
  <a href="prices.php" class="card">
    <h2><?= count($priceSecs) ?></h2>
    <p>Файлів цін</p>
  </a>
  <a href="backups.php" class="card">
    <h2><?= count($backups) ?></h2>
    <p>Бекапів збережено</p>
  </a>
</div>

<h2 style="font-size:13px;text-transform:uppercase;color:#888;letter-spacing:1.5px;margin-bottom:14px">
  Швидкі дії
</h2>
<div class="actions" style="flex-wrap:wrap;gap:10px">
  <a href="product-edit.php" class="btn btn-primary">+ Новий товар</a>
  <a href="service-edit.php" class="btn btn-primary">+ Нова послуга</a>
  <a href="prices.php" class="btn btn-ghost">Редагувати ціни</a>
  <a href="settings.php" class="btn btn-ghost">Змінити пароль</a>
</div>

<div class="hint-box" style="margin-top:30px">
  <strong>Як це працює:</strong> адмінка читає/зберігає файли
  <code>products.json</code> та <code>includes/data/*.php</code> напряму.
  Перед кожним збереженням створюється резервна копія в
  <code>admin/_backups/</code> — можна відкотитися з розділу «Бекапи».
</div>

<?php adminFooter(); ?>