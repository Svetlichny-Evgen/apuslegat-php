<?php
require __DIR__ . '/auth.php';
adminRequireLogin();
require __DIR__ . '/helpers.php';
require __DIR__ . '/layout.php';

$error = '';
$ok    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();
    $old = $_POST['old'] ?? '';
    $new1 = $_POST['new1'] ?? '';
    $new2 = $_POST['new2'] ?? '';

    $cfg = adminConfig();
    if (!password_verify($old, $cfg['password_hash'] ?? '')) {
        $error = 'Поточний пароль невірний';
    } elseif ($new1 !== $new2) {
        $error = 'Нові паролі не співпадають';
    } elseif (strlen($new1) < 6) {
        $error = 'Мінімальна довжина пароля — 6 символів';
    } elseif (adminChangePassword($new1)) {
        $ok = 'Пароль змінено. Наступного разу використовуйте новий.';
    } else {
        $error = 'Не вдалося зберегти';
    }
}

adminHeader('Налаштування', 'settings.php');
?>
<?php if ($error): ?><div class="alert alert-err"><?= e($error) ?></div><?php endif; ?>
<?php if ($ok):    ?><div class="alert alert-ok"><?= e($ok) ?></div><?php endif; ?>

<form method="POST" style="max-width:500px">
  <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">

  <div class="form-row">
    <label>Поточний пароль</label>
    <input type="password" name="old" required autocomplete="current-password">
  </div>
  <div class="form-row">
    <label>Новий пароль (мін. 6 символів)</label>
    <input type="password" name="new1" required minlength="6" autocomplete="new-password">
  </div>
  <div class="form-row">
    <label>Новий пароль ще раз</label>
    <input type="password" name="new2" required minlength="6" autocomplete="new-password">
  </div>

  <div class="btn-bar">
    <button type="submit" class="btn btn-primary">Змінити пароль</button>
  </div>
</form>

<div class="hint-box" style="margin-top:30px">
  Пароль зберігається у <code>admin/config.json</code> у вигляді bcrypt-хешу.
  Резервний вхід завжди можливий, якщо видалити <code>config.json</code> — тоді
  автоматично встановиться <code>admin123</code>.
</div>

<?php adminFooter(); ?>