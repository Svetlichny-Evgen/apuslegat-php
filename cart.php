<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/data/products-loader.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'catalog';           /* чтобы в шапке была иконка корзины */
$PAGE_TITLE = t('cart_page_title') . ' — APUS LEGAT';
$PAGE_DESC  = '';
$PAGE_CSS   = ['cart'];
$PAGE_JS    = ['main', 'cart'];
$HEAD_JS    = ['utm'];

/* ==== Плоский список товаров для fallback-поиска в cart.js ==== */
$productsFlat = [];
foreach (loadAllProducts() as $p) {
    $image = $p['image_url'] ? url($p['image_url']) : url('img/public/no-photo.png');

    $productsFlat[] = [
        'id'           => $p['id'],
        'parent_id'    => null,
        'name'         => $p['name'],
        'image_url'    => $image,
        'price'        => (int) $p['price'],
        'sku'          => '',
        'tacho_model'  => '',
        'voltage'      => '',
        'condition'    => $p['condition'] ?? 'new',
        'is_variation' => false,
    ];

    foreach ($p['variations'] as $v) {
        $productsFlat[] = [
            'id'           => $v['id']          ?? '',
            'parent_id'    => $p['id'],
            'name'         => $p['name'],
            'image_url'    => $image,
            'price'        => (int) ($v['price'] ?? 0),
            'sku'          => $v['sku']         ?? '',
            'tacho_model'  => $v['tacho_model'] ?? '',
            'voltage'      => $v['voltage']     ?? '',
            'condition'    => $v['condition']   ?? 'new',
            'is_variation' => true,
        ];
    }
}

require __DIR__ . '/includes/head.php';
?>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main class="cart-container">
    <h1 class="cart-title"><?= t('cart_title') ?></h1>

    <!-- ПУСТАЯ КОРЗИНА -->
    <div id="empty-view" class="empty-view" style="display:none">
      <h2><?= t('cart_empty') ?></h2>
      <a href="<?= url('catalog.php') ?>" class="back-link"><?= t('cart_back') ?></a>
    </div>

    <!-- С ТОВАРАМИ -->
    <div id="cart-content" class="cart-grid" style="display:none">
      <div class="cart-left">
        <div class="cart-items" id="items-list"></div>

        <form id="order-form"
              class="checkout-form-container"
              action="<?= url('php/telegram.php') ?>"
              method="POST"
              data-form-name="cart_order"
              data-thankyou="thank-you.html">

          <div class="form-title"><?= t('cart_form_title') ?></div>

          <input type="hidden" name="utm_source"   id="utm_source"   value="">
          <input type="hidden" name="utm_medium"   id="utm_medium"   value="">
          <input type="hidden" name="utm_campaign" id="utm_campaign" value="">
          <input type="hidden" name="utm_term"     id="utm_term"     value="">
          <input type="hidden" name="utm_content"  id="utm_content"  value="">
          <input type="hidden" name="cart_items"   id="cart_items"   value="">
          <input type="hidden" name="cart_total"   id="cart_total"   value="">
          <input type="hidden" name="cart_count"   id="cart_count"   value="">
          <input type="hidden" name="lang"         id="lang"         value="<?= e(LANG) ?>">

          <div class="form-grid">
            <div class="form-group">
              <label for="inp-name"><?= t('cart_lbl_name') ?> *</label>
              <input type="text" name="name" id="inp-name" required
                     placeholder="<?= e(t('cart_ph_name')) ?>">
            </div>
            <div class="form-group">
              <label for="inp-phone"><?= t('cart_lbl_phone') ?> *</label>
              <input type="tel" name="phone" id="inp-phone" required
                     placeholder="+38 (0__) ___ __ __">
            </div>
            <div class="form-group full">
              <label for="inp-delivery"><?= t('cart_lbl_delivery') ?></label>
              <select name="delivery" id="inp-delivery">
                <option value="np"><?= t('cart_deliv_np') ?></option>
                <option value="pickup"><?= t('cart_deliv_pickup') ?></option>
              </select>
            </div>
            <div class="form-group full">
              <label for="inp-address"><?= t('cart_lbl_address') ?> *</label>
              <input type="text" name="address" id="inp-address" required
                     placeholder="<?= e(t('cart_ph_address')) ?>">
            </div>
          </div>

          <button type="submit" class="checkout-btn" id="checkout-submit-btn">
            <?= t('cart_checkout') ?>
          </button>
        </form>
      </div>

      <div class="cart-summary">
        <div class="summary-title"><?= t('cart_summary') ?></div>
        <div class="summary-row">
          <span><?= t('cart_items') ?>:</span>
          <span id="sum-count">0</span>
        </div>
        <div class="summary-total">
          <span><?= t('cart_total') ?>:</span>
          <span id="total-price">0 <?= t('cat_currency') ?></span>
        </div>
      </div>
    </div>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <script>
    window.CART_NO_PHOTO = <?= json_encode(url('img/public/no-photo.png'), JSON_UNESCAPED_SLASHES) ?>;
    window.CART_CFG = {
      lang:     <?= json_encode(LANG, JSON_UNESCAPED_UNICODE) ?>,
      formName: 'cart_order',
      thankyou: <?= json_encode(url('thank-you.html'), JSON_UNESCAPED_SLASHES) ?>,
      products: <?= json_encode($productsFlat, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
      i18n: {
        currency:      <?= json_encode(t('cat_currency'),      JSON_UNESCAPED_UNICODE) ?>,
        priceAsk:      <?= json_encode(t('cat_price_ask'),     JSON_UNESCAPED_UNICODE) ?>,
        tachograph:    <?= json_encode(t('cart_tachograph'),   JSON_UNESCAPED_UNICODE) ?>,
        sending:       <?= json_encode(t('cart_sending'),      JSON_UNESCAPED_UNICODE) ?>,
        errSend:       <?= json_encode(t('cart_err_send'),     JSON_UNESCAPED_UNICODE) ?>,
        errNameDigits: <?= json_encode(t('cart_err_name'),     JSON_UNESCAPED_UNICODE) ?>,
        errPhone:      <?= json_encode(t('cart_err_phone'),    JSON_UNESCAPED_UNICODE) ?>,
        errAddress:    <?= json_encode(t('cart_err_address'),  JSON_UNESCAPED_UNICODE) ?>,
        empty:         <?= json_encode(t('cart_empty'),        JSON_UNESCAPED_UNICODE) ?>
      }
    };
  </script>
  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>
</body>
</html>