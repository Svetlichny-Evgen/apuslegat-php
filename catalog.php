<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'catalog';
$PAGE_TITLE = t('cat_page_title');
$PAGE_DESC  = t('cat_page_desc');
$PAGE_CSS   = ['catalog'];
$PAGE_JS    = ['main', 'catalog'];
$HEAD_JS    = ['utm'];

/* ==== Загружаем products.json ==== */
$inventory = [];
$jsonPath  = __DIR__ . '/products.json';
if (file_exists($jsonPath)) {
    $raw = json_decode(file_get_contents($jsonPath), true);
    if (is_array($raw) && !empty($raw['inventory'])) {
        $inventory = $raw['inventory'];
    }
}

/* ==== Готовим продукты ==== */
$groupMap = [
    'used_tachographs' => 'tachographs',
    'new_tachographs'  => 'tachographs',
    'sensors'          => 'sensors',
    'parts'            => 'parts',
    'radio'            => 'radio',
    'antennas'         => 'antennas',
    // installation_kits остаётся как есть
];

$products = [];

foreach ($inventory as $key => $items) {
    if (!is_array($items)) continue;
    $group = $groupMap[$key] ?? $key;

    foreach ($items as $item) {
        $id = $item['id'] ?? '';
        $hasVariations = !empty($item['has_variations']) && !empty($item['variations']);

        if ($hasVariations) {
            $variations = $item['variations'];
            $prices     = array_map(fn($v) => (int) ($v['price'] ?? 0), $variations);
            $prices     = array_filter($prices, fn($p) => $p > 0);
            $minPrice   = $prices ? min($prices) : 0;
            $maxPrice   = $prices ? max($prices) : 0;
            $conditions = array_values(array_unique(array_map(fn($v) => $v['condition'] ?? 'new', $variations)));

            $products[] = [
                'id'              => $id,
                'name'            => $item['name']        ?? [],
                'description'     => $item['description'] ?? [],
                'image_url'       => $item['image_url']   ?? '',
                'group'           => $group,
                'has_variations'  => true,
                'variations'      => $variations,
                'conditions'      => $conditions,
                'min_price'       => $minPrice,
                'max_price'       => $maxPrice,
                'price'           => 0,
                'is_kit'          => strpos((string) $id, 'kit-') === 0,
                'default_variation' => $variations[0] ?? null,
            ];
        } else {
            $cond = $item['condition'] ?? 'new';
            $products[] = [
                'id'             => $id,
                'name'           => $item['name']        ?? [],
                'description'    => $item['description'] ?? [],
                'image_url'      => $item['image_url']   ?? '',
                'group'          => $group,
                'has_variations' => false,
                'variations'     => [],
                'conditions'     => [$cond],
                'min_price'      => 0,
                'max_price'      => 0,
                'price'          => (int) ($item['price'] ?? 0),
                'is_kit'         => false,
            ];
        }
    }
}

/* ==== Хелпер: URL товара ==== */
function productUrl(array $p): string {
    $params = ['id' => $p['id']];
    if ($p['has_variations'] && $p['default_variation']) {
        if (!empty($p['default_variation']['voltage']))   $params['volt'] = $p['default_variation']['voltage'];
        if (!empty($p['default_variation']['condition'])) $params['cond'] = $p['default_variation']['condition'];
    }
    return url('product.php') . '?' . http_build_query($params);
}

/* ==== Хелпер: цена ==== */
function productPrice(array $p): string {
    if ($p['has_variations']) {
        $min = $p['min_price'];
        $max = $p['max_price'];
        if ($min > 0 && $max > 0 && $min !== $max) return "$min - $max " . t('cat_currency');
        if ($min > 0) return "$min " . t('cat_currency');
    } else {
        if ($p['price'] > 0) return $p['price'] . ' ' . t('cat_currency');
    }
    return t('cat_price_ask');
}

/* ==== Категории ==== */
$categories = [
    ['id' => 'all',               'label' => 'cat_filter_all'],
    ['id' => 'tachographs',       'label' => 'cat_filter_tachographs'],
    ['id' => 'sensors',           'label' => 'cat_filter_sensors'],
    ['id' => 'parts',             'label' => 'cat_filter_parts'],
    ['id' => 'installation_kits', 'label' => 'cat_filter_kits'],
    ['id' => 'antennas',          'label' => 'cat_filter_antennas'],
    ['id' => 'radio',             'label' => 'cat_filter_radio'],
];

require __DIR__ . '/includes/head.php';
?>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main class="catalog-section">
    <div class="section-header">
      <h1><?= t('cat_h1') ?></h1>
    </div>

    <div class="filters-container">
      <div class="filter-label"><?= t('cat_lbl_cat') ?></div>
      <div class="filter-group" id="cat-filters">
        <?php foreach ($categories as $i => $c): ?>
          <button class="f-btn <?= $i === 0 ? 'active' : '' ?>"
                  data-cat="<?= e($c['id']) ?>"><?= t($c['label']) ?></button>
        <?php endforeach; ?>
      </div>

      <div class="filter-label"><?= t('cat_lbl_cond') ?></div>
      <div class="filter-group" id="cond-filters">
        <button class="f-btn active" data-cond="all"><?= t('cat_cond_all') ?></button>
        <button class="f-btn"        data-cond="new"><?= t('cat_cond_new') ?></button>
        <button class="f-btn"        data-cond="used"><?= t('cat_cond_used') ?></button>
      </div>
    </div>

    <div class="products-grid">
      <?php foreach ($products as $p):
          $hasVars = $p['has_variations'];

          /* --- Бейджи --- */
          $leftBadge  = '';
          $rightBadge = '';

          if ($hasVars) {
              if ($p['is_kit']) {
                  // Комплект — левый бейдж фиолетовый, без бейджа справа
                  $leftBadge = '<div class="p-badge p-badge-left" style="background:#6c5ce7">'
                             . t('cat_kit_badge') . '</div>';
              } else {
                  // «N вариантов»
                  $varCount  = count($p['variations']);
                  $leftBadge = '<div class="p-badge p-badge-left" style="background:#6c5ce7">'
                             . pluralVariants($varCount, LANG) . '</div>';

                  // Если все варианты — одного состояния → бейдж справа
                  if (count($p['conditions']) === 1) {
                      $cond      = $p['conditions'][0];
                      $isUsed    = ($cond === 'used');
                      $condColor = $isUsed ? '#333' : 'var(--red)';
                      $condText  = $isUsed ? t('cat_used') : t('cat_new');
                      $rightBadge = '<div class="p-badge p-badge-right" style="background:' . $condColor . '">'
                                  . e($condText) . '</div>';
                  }
              }
          } else {
              // Без вариаций — бейдж состояния слева
              $cond      = $p['conditions'][0] ?? 'new';
              $isUsed    = ($cond === 'used');
              $condColor = $isUsed ? '#333' : 'var(--red)';
              $condText  = $isUsed ? t('cat_used') : t('cat_new');
              $leftBadge = '<div class="p-badge p-badge-left" style="background:' . $condColor . '">'
                         . e($condText) . '</div>';
          }

          /* --- Тексты --- */
          $name = $p['name'][LANG]        ?? $p['name']['uk']        ?? '';
          $desc = $p['description'][LANG] ?? $p['description']['uk'] ?? '';
          $img  = $p['image_url'] ?: url('img/public/no-photo.png');
          $link = productUrl($p);
          $price = productPrice($p);
          $condAttr = implode(' ', $p['conditions']);

          $btnAction = $hasVars ? 'navigate' : 'add';
          $btnText   = $hasVars ? t('cat_detail') : t('cat_order');
      ?>
        <a href="<?= e($link) ?>" class="p-link">
          <div class="p-card"
               data-cat="<?= e($p['group']) ?>"
               data-conditions="<?= e($condAttr) ?>">
            <?= $leftBadge ?>
            <?= $rightBadge ?>
            <div class="p-img-box">
              <div class="p-img">
                <img src="<?= e($img) ?>" alt="<?= e($name) ?>"
                     onerror="this.src='<?= url('img/public/no-photo.png') ?>'">
              </div>
            </div>
            <div class="p-info">
              <div class="p-title"><?= e($name) ?></div>
              <div class="p-desc"><?= e($desc) ?></div>
              <div class="p-footer">
                <div class="p-price"><?= e($price) ?></div>
                <button type="button"
                        class="p-buy"
                        data-action="<?= $btnAction ?>"
                        data-id="<?= e($p['id']) ?>"><?= e($btnText) ?></button>
              </div>
            </div>
          </div>
        </a>
      <?php endforeach; ?>

      <div id="empty-state" class="empty-state hidden">
        <h2><?= t('cat_nothing') ?></h2>
      </div>
    </div>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>
</body>
</html>