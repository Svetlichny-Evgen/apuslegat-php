<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'tacho-install';
$PAGE_TITLE = t('tins_page_title');
$PAGE_DESC  = t('tins_page_desc');
$PAGE_CSS   = ['tacho-install'];
$PAGE_JS    = ['main', 'tacho-install'];
$HEAD_JS    = ['utm'];

$d = require __DIR__ . '/includes/data/tacho-install.php';
$brandLabel = function (string $code) use ($d): string {
    return $d['brand_labels'][$code] ?? $code;
};

require __DIR__ . '/includes/head.php';
?>
<body>
  <noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N89VVZFR"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
  </noscript>

  <?php require __DIR__ . '/includes/header.php'; ?>

  <!-- HERO-SUB -->
  <section class="hero-sub">
    <div class="container">
      <a href="<?= url('service-tacho.php') ?>" class="btn-back-link"><?= t('tins_back') ?></a>
      <h1 class="hero-title"><?= t('tins_h1') ?></h1>
      <p class="hero-sub-text"><?= t('tins_lead') ?></p>
      <div class="line"></div>
    </div>
  </section>

  <!-- EXP -->
  <section class="s-experience">
    <div class="container">
      <div class="exp-grid">
        <div class="exp-item"><b>5+</b><span><?= t('tins_exp1') ?></span></div>
        <div class="exp-item"><b>550+</b><span><?= t('tins_exp2') ?></span></div>
        <div class="exp-item"><b>2 <?= t('tins_exp3b') ?></b><span><?= t('tins_exp3') ?></span></div>
      </div>
    </div>
  </section>

  <!-- ADVANTAGES -->
  <section class="s-advantages">
    <div class="container">
      <div class="advantages-grid">
        <div class="advantage-card">
          <span class="adv-icon">🗂️</span>
          <h3><?= t('tins_adv1_t') ?></h3>
          <p><?= t('tins_adv1_d') ?></p>
        </div>
        <div class="advantage-card">
          <span class="adv-icon">🧾</span>
          <h3><?= t('tins_adv2_t') ?></h3>
          <p><?= t('tins_adv2_d') ?></p>
        </div>
        <div class="advantage-card">
          <span class="adv-icon">✅</span>
          <h3><?= t('tins_adv3_t') ?></h3>
          <p><?= t('tins_adv3_d') ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- CALC -->
  <section class="calc-section">
    <div class="taho-calc-wrapper">
      <div class="taho-calc-header">
        <h3 class="taho-calc-title"><?= t('tins_calc_title') ?></h3>
        <p class="taho-calc-subtitle"><?= t('tins_calc_subtitle') ?></p>
        <p class="taho-calc-note"><?= t('tins_calc_note') ?></p>
      </div>

      <div class="taho-calc-grid">
        <div class="taho-input-group">
          <label><?= t('tins_brand_label') ?></label>
          <select id="taho-brand" class="taho-select">
            <option value="" disabled selected><?= t('tins_brand_choose') ?></option>
            <optgroup label="<?= t('tins_group_trucks') ?>">
              <?php foreach ($d['brands_trucks'] as $code): ?>
                <option value="<?= e($code) ?>"><?= e($brandLabel($code)) ?></option>
              <?php endforeach; ?>
            </optgroup>
            <optgroup label="<?= t('tins_group_vans') ?>">
              <?php foreach ($d['brands_vans'] as $code): ?>
                <option value="<?= e($code) ?>"><?= e($brandLabel($code)) ?></option>
              <?php endforeach; ?>
            </optgroup>
          </select>
        </div>

        <div class="taho-input-group">
          <label><?= t('tins_voltage_label') ?></label>
          <div class="taho-radio-group">
            <label class="taho-radio-label">
              <input type="radio" name="taho-voltage" value="12"><span>12V</span>
            </label>
            <label class="taho-radio-label">
              <input type="radio" name="taho-voltage" value="24"><span>24V</span>
            </label>
          </div>
        </div>
      </div>

      <div class="taho-calc-footer">
        <div class="taho-price-block">
          <span class="taho-price-label"><?= t('tins_price_label') ?></span>
          <span id="taho-total-price" class="taho-price-value hint"><?= t('tins_brand_hint') ?></span>
        </div>
        <button id="taho-order-btn" class="taho-btn-primary" disabled>
          <?= t('tins_order_btn') ?>
        </button>
      </div>
    </div>
  </section>

  <!-- SMART -->
  <section class="s-smart">
    <div class="container">
      <div class="smart-card">
        <div class="smart-text">
          <h3><?= t('tins_smart_title') ?></h3>
          <p><?= t('tins_smart_desc') ?></p>
          <ul class="smart-list">
            <li><?= t('tins_smart_li1') ?></li>
            <li><?= t('tins_smart_li2') ?></li>
            <li><?= t('tins_smart_li3') ?></li>
            <li><?= t('tins_smart_li4') ?></li>
          </ul>
        </div>
        <div class="smart-actions">
          <span class="smart-label"><?= t('tins_smart_label') ?></span>
          <a href="tel:+380660339009" class="smart-btn smart-btn-call">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
              <path d="M6.6 10.8a15.1 15.1 0 006.6 6.6l2.2-2.2a1 1 0 011-.25 11.4 11.4 0 003.6.57 1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.25.2 2.45.57 3.57a1 1 0 01-.25 1z"/>
            </svg>
            +380 66 033 90 09
          </a>
          <div class="smart-phones">
            <a href="tel:+380630339009">+380 63 033 90 09</a>
          </div>
          <button type="button" class="smart-btn smart-btn-order" id="smartOrderBtn">
            <?= t('tins_smart_order') ?>
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- WORK STEPS -->
  <section class="s-work">
    <div class="container">
      <h2><?= t('tins_work_title') ?></h2>
      <div class="work-grid">
        <?php foreach ($d['steps'] as $s): ?>
          <div class="work-card">
            <span class="work-num"><?= e($s['num']) ?></span>
            <h4><?= t($s['t']) ?></h4>
            <p><?= t($s['d']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- REVIEWS -->
  <section class="s-reviews">
    <div class="container">
      <h2 class="reviews-title"><?= t('tins_reviews_title') ?></h2>
      <div class="reviews-grid">
        <?php foreach ($d['reviews'] as $r): ?>
          <div class="review-card">
            <div class="review-stars">★★★★★</div>
            <p class="review-text">«<?= t($r['text']) ?>»</p>
            <span class="review-author"><?= t($r['name']) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- MODAL -->
  <div id="consultModal" class="modal">
    <div class="modal-content">
      <button class="modal-close" aria-label="close">&times;</button>
      <h3><?= t('tins_modal_title') ?></h3>
      <div class="modal-desc"><?= t('tins_modal_desc') ?></div>
      <div class="modal-input-group">
        <label for="modalName"><?= t('tins_modal_name') ?> <span class="req">*</span></label>
        <input type="text" id="modalName"
               placeholder="<?= e(t('tins_name_ph')) ?>"
               autocomplete="name">
        <div class="modal-error" id="modalNameError"></div>
      </div>
      <div class="modal-input-group">
        <label for="modalPhone"><?= t('tins_modal_phone') ?> <span class="req">*</span></label>
        <input type="tel" id="modalPhone" placeholder="+380" maxlength="20" autocomplete="tel">
        <div class="modal-error" id="modalPhoneError"></div>
      </div>
      <button class="modal-submit" id="modalSubmitBtn"><?= t('tins_modal_submit') ?></button>
    </div>
  </div>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <script>
    window.TACHO_INSTALL_CONFIG = {
      endpoint: <?= json_encode(url('php/telegram.php'), JSON_UNESCAPED_SLASHES) ?>,
      thankyou: <?= json_encode(url('thank-you.html'),    JSON_UNESCAPED_SLASHES) ?>
    };
    window.TACHO_INSTALL_DATA = {
      prices: <?= json_encode($d['prices'], JSON_UNESCAPED_UNICODE) ?>
    };
    window.TACHO_INSTALL_I18N = {
      selectBrandHint:   <?= json_encode(t('tins_brand_hint'),     JSON_UNESCAPED_UNICODE) ?>,
      selectVoltageHint: <?= json_encode(t('tins_voltage_hint'),   JSON_UNESCAPED_UNICODE) ?>,
      notSupported:      <?= json_encode(t('tins_not_supported'),  JSON_UNESCAPED_UNICODE) ?>,
      errNameShort:      <?= json_encode(t('tins_err_name_short'), JSON_UNESCAPED_UNICODE) ?>,
      errNameChars:      <?= json_encode(t('tins_err_name_chars'), JSON_UNESCAPED_UNICODE) ?>,
      errPhoneEmpty:     <?= json_encode(t('tins_err_phone'),      JSON_UNESCAPED_UNICODE) ?>,
      errPhoneFormat:    <?= json_encode(t('tins_err_phone_fmt'),  JSON_UNESCAPED_UNICODE) ?>,
      sending:           <?= json_encode(t('tins_sending'),        JSON_UNESCAPED_UNICODE) ?>,
      errSendFailed:     <?= json_encode(t('tins_err_send'),       JSON_UNESCAPED_UNICODE) ?>,
      modalSubmitBtn:    <?= json_encode(t('tins_modal_submit'),   JSON_UNESCAPED_UNICODE) ?>
    };
  </script>
  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>
</body>
</html>