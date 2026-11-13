<?php
require __DIR__ . '/auth.php';
adminRequireLogin();
require __DIR__ . '/helpers.php';
require __DIR__ . '/layout.php';

$inventory = loadJson(productsPath());
$inv       = $inventory['inventory'] ?? [];

adminHeader('Товари', 'products.php');
?>
<div class="actions" style="margin-bottom:20px">
  <a href="product-edit.php" class="btn btn-primary">+ Новий товар</a>
</div>

<?php if (!$inv): ?>
  <div class="hint-box">
    Файл <code>products.json</code> порожній або не знайдено.
    Натисніть «+ Новий товар», щоб створити перший.
  </div>
<?php endif; ?>

<?php foreach ($inv as $cat => $items):
    if (!is_array($items) || !$items) continue;
?>
  <h2 style="font-size:13px;color:#e60c0c;text-transform:uppercase;letter-spacing:1.5px;margin:24px 0 10px">
    <?= e($cat) ?> <span class="muted">(<?= count($items) ?>)</span>
  </h2>
  <table>
    <thead>
      <tr>
        <th style="width:130px">ID</th>
        <th>Назва</th>
        <th style="width:130px">Ціна</th>
        <th style="width:90px">Варіації</th>
        <th style="width:170px">Дії</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $p):
          $price = '—';
          if (!empty($p['has_variations']) && !empty($p['variations'])) {
              $pv = array_filter(array_map(fn($v) => (int)($v['price'] ?? 0), $p['variations']));
              $price = $pv ? min($pv) . ' – ' . max($pv) . ' грн' : '—';
          } elseif (!empty($p['price'])) {
              $price = (int)$p['price'] . ' грн';
          }
          $varCount = !empty($p['has_variations']) ? count($p['variations'] ?? []) : 0;
      ?>
        <tr>
          <td><span class="code"><?= e($p['id']) ?></span></td>
          <td><?= e($p['name']['uk'] ?? $p['name']['ru'] ?? '—') ?></td>
          <td><?= e($price) ?></td>
          <td><?= $varCount ?: '—' ?></td>
          <td class="actions">
            <a href="product-edit.php?id=<?= urlencode($p['id']) ?>" class="btn btn-ghost btn-sm">Редагувати</a>
            <form method="POST" action="product-delete.php"
                  onsubmit="return confirm('Видалити «<?= e($p['id']) ?>»?')">
              <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
              <input type="hidden" name="id" value="<?= e($p['id']) ?>">
              <button type="submit" class="btn btn-danger btn-sm">×</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endforeach; ?>

<?php adminFooter(); ?>