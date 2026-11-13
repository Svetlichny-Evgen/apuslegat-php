<?php
require __DIR__ . '/auth.php';
adminRequireLogin();
require __DIR__ . '/helpers.php';
require __DIR__ . '/layout.php';

$servicesPath = rootPath('includes/data/services.php');
$ukPath       = rootPath('includes/lang/uk.php');
$ruPath       = rootPath('includes/lang/ru.php');

$services = loadPhpArray($servicesPath);
$uk       = loadPhpArray($ukPath);
$ru       = loadPhpArray($ruPath);

adminHeader('Послуги', 'services.php');
?>
<div class="hint-box">
  Кожна послуга — це запис у <code>includes/data/services.php</code>
  + ключі перекладів у <code>includes/lang/uk.php</code> і <code>ru.php</code>.
  Адмінка синхронізує всі три файли автоматично.
</div>

<div class="actions" style="margin-bottom:20px">
  <a href="service-edit.php" class="btn btn-primary">+ Нова послуга</a>
</div>

<table>
  <thead>
    <tr>
      <th style="width:50px">№</th>
      <th style="width:180px">Файл</th>
      <th>Заголовок (UA)</th>
      <th style="width:110px">Фільтри</th>
      <th style="width:130px">Ціна (UA)</th>
      <th style="width:160px">Дії</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($services as $i => $s): ?>
      <tr>
        <td><strong style="color:#e60c0c"><?= e($s['num'] ?? '') ?></strong></td>
        <td><span class="code"><?= e($s['file'] ?? '') ?></span></td>
        <td><?= e($uk[$s['t'] ?? ''] ?? '—') ?></td>
        <td style="font-size:11px;color:#888"><?= e($s['n'] ?? '') ?></td>
        <td><?= e($uk[$s['p'] ?? ''] ?? '') ?></td>
        <td class="actions">
          <a href="service-edit.php?idx=<?= $i ?>" class="btn btn-ghost btn-sm">Редагувати</a>
          <form method="POST" action="service-delete.php"
                onsubmit="return confirm('Видалити послугу «<?= e($uk[$s['t'] ?? ''] ?? '') ?>»?')">
            <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="idx" value="<?= $i ?>">
            <button type="submit" class="btn btn-danger btn-sm">×</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php adminFooter(); ?>