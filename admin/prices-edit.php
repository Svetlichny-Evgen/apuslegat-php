<?php
require __DIR__ . '/auth.php';
adminRequireLogin();
require __DIR__ . '/helpers.php';
require __DIR__ . '/layout.php';

$section = $_GET['s'] ?? '';
if (!$section || !preg_match('/^[a-z0-9\-_]+$/i', $section)) {
    redirect('prices.php');
}
$path = rootPath("includes/data/{$section}.php");
if (!is_file($path)) {
    flash('Файл не знайдено', 'err');
    redirect('prices.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();
    $raw  = $_POST['json'] ?? '';
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        $error = 'Помилка JSON: ' . json_last_error_msg();
    } else {
        backupFile($path);
        if (savePhpArray($path, $data)) {
            flash('Файл збережено');
            redirect('prices-edit.php?s=' . urlencode($section));
        }
        $error = 'Не вдалося записати файл (перевірте права)';
    }
}

$data = loadPhpArray($path);
$json = json_encode(
    $data,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);

adminHeader('Ціни: ' . $section, 'prices.php');
?>
<?php if ($error): ?>
  <div class="alert alert-err"><?= e($error) ?></div>
<?php endif; ?>

<div class="hint-box">
  Редагуйте JSON. При збереженні він конвертується назад у
  <code>return [...];</code> у файлі <code>includes/data/<?= e($section) ?>.php</code>.
  <br><strong style="color:#ffb">Увага:</strong> не видаляйте обов'язкові ключі
  (наприклад <code>prices</code>, <code>options</code>) — інакше сторінка на сайті зламається.
</div>

<form method="POST">
  <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
  <textarea name="json" class="price-editor" spellcheck="false"><?= e($json) ?></textarea>
  <div class="btn-bar">
    <button type="submit" class="btn btn-primary">Зберегти</button>
    <a href="prices.php" class="btn btn-ghost">← До списку</a>
    <a href="prices-edit.php?s=<?= urlencode($section) ?>" class="btn btn-ghost"
       onclick="return confirm('Скасувати всі незбережені зміни?')">Скинути</a>
  </div>
</form>

<?php adminFooter(); ?>