<?php
require __DIR__ . '/auth.php';
adminRequireLogin();
require __DIR__ . '/helpers.php';
require __DIR__ . '/layout.php';

$list = listBackups();

adminHeader('Бекапи', 'backups.php');
?>
<div class="hint-box">
  Перед кожним збереженням у файл створюється копія в <code>admin/_backups/</code>.
  Тут можна відновити будь-яку версію.
</div>

<?php if (!$list): ?>
  <div class="muted" style="padding:30px;text-align:center">Ще немає жодного бекапу.</div>
<?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Файл</th>
        <th style="width:180px">Дата</th>
        <th style="width:100px">Розмір</th>
        <th style="width:130px">Дії</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($list as $b):
          [$origFile, $tsPart] = explode('.', $b['file'], 2);
      ?>
        <tr>
          <td>
            <span class="code"><?= e($origFile) ?></span>
            <div class="muted" style="margin-top:3px;font-size:11px">
              <?= e($tsPart) ?>
            </div>
          </td>
          <td><?= date('d.m.Y H:i:s', $b['time']) ?></td>
          <td><?= round($b['size']/1024, 1) ?> КБ</td>
          <td>
            <form method="POST" action="backup-restore.php"
                  onsubmit="return confirm('Відновити файл з цього бекапу? Поточна версія буде збережена в новий бекап.')">
              <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
              <input type="hidden" name="backup" value="<?= e($b['file']) ?>">
              <button type="submit" class="btn btn-ghost btn-sm">Відновити</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?php adminFooter(); ?>