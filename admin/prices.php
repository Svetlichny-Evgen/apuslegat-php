<?php
require __DIR__ . '/auth.php';
adminRequireLogin();
require __DIR__ . '/helpers.php';
require __DIR__ . '/layout.php';

$sections = [
    'otk-prices'            => ['Ціни ОТК',                    'Таблиця цін послуг техогляду (UA + RU) та опції для форми'],
    'tacho-page'            => ['Тахосервіс — ціни та відгуки', 'Ціни, опції, відгуки, бренди для сторінки service-tacho'],
    'tacho-install'         => ['Калькулятор тахографів',      'Бренди, ціни за моделями/напругою, кроки, відгуки'],
    'parking'               => ['Паркінг — тарифи',            'Іконки, погодинні/місячні/спец тарифи, опції форми'],
    'walkie-talkie-install' => ['Встановлення рацій',          'Ціни, опції, кроки'],
    'service-extra'         => ['Додаткові послуги',           'Картки (зображення + текст), опції форми'],
    'service-cert-auto'     => ['Сертифікація авто',           'Напрямки, вимоги, документи, опції'],
    'service-cert-tanks'    => ['Сертифікація цистерн ADR',    'Методи, галерея, типи, результати, опції'],
    'service-heaters'       => ['Автономні опалювачі',         'Ціни, опції, кроки, сервіс-лист'],
];

adminHeader('Ціни', 'prices.php');
?>
<div class="hint-box">
  Кожна секція редагується як <strong>JSON</strong> з живою структурою файлу.
  Перед збереженням створюється бекап — можна відкотитися.
</div>

<table>
  <thead>
    <tr>
      <th style="width:280px">Секція</th>
      <th>Що всередині</th>
      <th style="width:140px">Файл</th>
      <th style="width:150px">Дії</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($sections as $key => [$label, $desc]):
        $path = rootPath("includes/data/{$key}.php");
        $exists = is_file($path);
    ?>
      <tr>
        <td><strong><?= e($label) ?></strong></td>
        <td class="muted"><?= e($desc) ?></td>
        <td><span class="code"><?= e($key) ?>.php</span></td>
        <td>
          <?php if ($exists): ?>
            <a href="prices-edit.php?s=<?= urlencode($key) ?>" class="btn btn-ghost btn-sm">
              Редагувати
            </a>
          <?php else: ?>
            <span class="muted">Файл не знайдено</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php adminFooter(); ?>