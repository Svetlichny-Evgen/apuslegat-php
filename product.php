<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/data/products-loader.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

/* ==== Диагностика ==== */
if (isset($_GET['debug']) && $_GET['debug'] === '1') {
    header('Content-Type: text/plain; charset=utf-8');
    $all = loadAllProducts();
    echo "products.json path : " . (_findProductsJsonPath() ?? 'NOT FOUND') . "\n";
    echo "total products     : " . count($all) . "\n\n";

    $id = isset($_GET['id']) ? (string) $_GET['id'] : '';
    echo "REQUESTED id       : [$id]\n";
    echo "findProductById    : " . (findProductById($id) ? 'FOUND' : 'NOT FOUND') . "\n\n";

    echo "First 10 product ids in map:\n";
    foreach (array_slice(array_keys($all), 0, 10) as $pid) {
        echo "  [$pid]\n";
    }

    echo "\nFirst 10 variation ids in map:\n";
    $count = 0;
    foreach ($all as $p) {
        foreach (($p['variations'] ?? []) as $v) {
            echo "  [" . ($v['id'] ?? '?') . "]  (product: " . $p['id'] . ")\n";
            if (++$count >= 10) break 2;
        }
    }
    exit;
}

/* ==== Читаем id из URL ==== */
$productId = isset($_GET['id']) ? (string) $_GET['id'] : '';

$found = $productId !== '' ? findProductWithVariation($productId) : null;

if (!$found) {
    $PAGE       = 'catalog';
    $PAGE_TITLE = t('prod_not_found_title') . ' — APUS LEGAT';
    $PAGE_DESC  = '';
    $PAGE_CSS   = ['product'];
    $PAGE_JS    = ['main'];

    require __DIR__ . '/includes/head.php';
    ?>
    <body>
      <?php require __DIR__ . '/includes/header.php'; ?>
      <main class="product-container">
        <div class="pd-empty">
          <h2><?= t('prod_not_found_title') ?></h2>
          <a href="<?= url('catalog.php') ?>"><?= t('prod_back_to_catalog') ?></a>
        </div>
      </main>
      <?php require __DIR__ . '/includes/footer.php'; ?>
    </body>
    </html>
    <?php
    exit;
}

$product = $found['product'];
$variationFromId = $found['variation']; // если id был variation.id

/* ==== Активная вариация ==== */
$activeVariation = $variationFromId ?: findActiveVariation($product, $_GET);

/* ==== Заголовки ==== */
$productName = $product['name'][LANG] ?? $product['name']['uk'] ?? 'Товар';
$PAGE       = 'catalog';
$PAGE_TITLE = $productName . ' — APUS LEGAT';
$__desc = (string) ($product['description'][LANG] ?? $product['description']['uk'] ?? '');
$PAGE_DESC  = function_exists('mb_substr') ? mb_substr($__desc, 0, 160) : substr($__desc, 0, 160);
$PAGE_CSS   = ['product'];
$PAGE_JS    = ['main', 'product'];
$HEAD_JS    = ['utm'];

/* ==== Публичные данные для JS ==== */
$productJs = [
    'id'             => $product['id'],
    'name'           => $product['name'],
    'description'    => $product['description'],
    'image_url'      => $product['image_url']
        ? url($product['image_url'])
        : url('img/public/no-photo.png'),
    'price'          => $product['price'],
    'condition'      => $product['condition'],
    'has_variations' => $product['has_variations'],
    'is_kit'         => $product['is_kit'],
    'variations'     => array_map(function ($v) {
        return [
            'id'          => $v['id']          ?? '',
            'price'       => (int) ($v['price'] ?? 0),
            'condition'   => $v['condition']   ?? '',
            'voltage'     => $v['voltage']     ?? '',
            'tacho_model' => $v['tacho_model'] ?? '',
            'sku'         => $v['sku']         ?? '',
            'description' => $v['description'] ?? null,
        ];
    }, $product['variations']),
];

$activeVariationJs = $activeVariation ? [
    'id'          => $activeVariation['id']          ?? '',
    'price'       => (int) ($activeVariation['price'] ?? 0),
    'condition'   => $activeVariation['condition']   ?? '',
    'voltage'     => $activeVariation['voltage']     ?? '',
    'tacho_model' => $activeVariation['tacho_model'] ?? '',
    'sku'         => $activeVariation['sku']         ?? '',
    'description' => $activeVariation['description'] ?? null,
] : null;

require __DIR__ . '/includes/head.php';
?>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main class="product-container" id="product-view">
    <div class="back-link-wrapper">
      <a href="<?= url('catalog.php') ?>" class="back-link"><?= t('prod_back') ?></a>
    </div>

    <div class="product-image-side">
      <img src="<?= e($productJs['image_url']) ?>"
           alt="<?= e($productName) ?>"
           onerror="this.src='<?= url('img/public/no-photo.png') ?>'">
    </div>

    <div class="product-info-side">
      <?php
      $displayName = $productName;
      if ($activeVariation) {
          $parts = [];
          if ($product['is_kit'] && !empty($activeVariation['tacho_model'])) {
              $parts[] = 'Тахограф ' . $activeVariation['tacho_model'];
          }
          if (!empty($activeVariation['voltage'])) $parts[] = $activeVariation['voltage'];
          if (!empty($parts)) $displayName .= ' (' . implode(', ', $parts) . ')';
      }
      $priceText = $activeVariation && $activeVariation['price'] > 0
          ? $activeVariation['price'] . ' ' . t('cat_currency')
          : ($product['price'] > 0 ? $product['price'] . ' ' . t('cat_currency') : t('cat_price_ask'));
      $condText = (($activeVariation['condition'] ?? $product['condition']) === 'used')
          ? t('cat_used') : t('cat_new');
      ?>
      <div class="status-badge"><?= e($condText) ?></div>
      <h1 class="product-title"><?= e($displayName) ?></h1>
      <div class="product-price"><?= e($priceText) ?></div>
      <div class="action-btns">
        <button class="pd-btn" type="button" id="pd-add-to-cart" disabled>
          <?= t('prod_order') ?>
        </button>
      </div>
    </div>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <script>
    window.PRODUCT_CFG = {
      lang: <?= json_encode(LANG, JSON_UNESCAPED_UNICODE) ?>,
      product: <?= json_encode($productJs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
      activeVariation: <?= json_encode($activeVariationJs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
      i18n: {
        currency:      <?= json_encode(t('cat_currency'),     JSON_UNESCAPED_UNICODE) ?>,
        priceAsk:      <?= json_encode(t('cat_price_ask'),    JSON_UNESCAPED_UNICODE) ?>,
        used:          <?= json_encode(t('cat_used'),         JSON_UNESCAPED_UNICODE) ?>,
        new:           <?= json_encode(t('cat_new'),          JSON_UNESCAPED_UNICODE) ?>,
        order:         <?= json_encode(t('prod_order'),       JSON_UNESCAPED_UNICODE) ?>,
        kit:           <?= json_encode(t('prod_kit'),         JSON_UNESCAPED_UNICODE) ?>,
        selectVariant: <?= json_encode(t('prod_select_var'),  JSON_UNESCAPED_UNICODE) ?>,
        warrantyTitle: <?= json_encode(t('prod_warranty_h'),  JSON_UNESCAPED_UNICODE) ?>,
        warrantyText:  <?= json_encode(t('prod_warranty_t'),  JSON_UNESCAPED_UNICODE) ?>,
        returnTitle:   <?= json_encode(t('prod_return_h'),    JSON_UNESCAPED_UNICODE) ?>,
        returnText:    <?= json_encode(t('prod_return_t'),    JSON_UNESCAPED_UNICODE) ?>,
        deliveryTitle: <?= json_encode(t('prod_delivery_h'),  JSON_UNESCAPED_UNICODE) ?>,
        deliveryText:  <?= json_encode(t('prod_delivery_t'),  JSON_UNESCAPED_UNICODE) ?>
      }
    };
  </script>
  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>

  <script src="https://widgets.binotel.com/getcall/widgets/cqly9w3m14g5jz2txije.js" async></script>
</body>
</html>