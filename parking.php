<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'parking';
$PAGE_TITLE = t('park_page_title');
$PAGE_DESC  = t('park_page_desc');
$PAGE_CSS   = ['parking'];
$PAGE_JS    = ['main', 'parking'];
$HEAD_JS    = ['utm'];

$d = require __DIR__ . '/includes/data/parking.php';

require __DIR__ . '/includes/head.php';
?>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <!-- HERO -->
  <section class="hero-infra">
    <div class="container">
      <h1 class="hero-title"><?= t('park_h1') ?></h1>
      <div class="line-red"></div>
      <p><?= t('park_subtitle') ?></p>
    </div>
  </section>

  <!-- ICONS -->
  <section class="s-icons">
    <div class="container">
      <div class="icon-grid">
        <?php foreach ($d['icons'] as $ic): ?>
          <div class="icon-item">
            <div class="icon-box">
              <img src="<?= e($ic['img']) ?>" alt="<?= e($ic['alt']) ?>" loading="lazy">
            </div>
            <h4><?= t($ic['h']) ?></h4>
            <p><?= t($ic['p']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- PRICES -->
  <section class="s-prices">
    <div class="container">
      <h2 class="section-h2"><?= t('park_daily_h') ?></h2>
      <div class="line-red"></div>
      <table class="price-table">
        <?php foreach ($d['daily'] as $row): ?>
          <tr>
            <td><?= t($row['label']) ?></td>
            <td class="price-val">
              <?= e($row['price']) ?>
              <span class="unit"><?= t('park_unit_day') ?></span>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>

      <h2 class="section-h2"><?= t('park_abon_h') ?></h2>
      <div class="line-red"></div>

      <div class="info-card">
        <h3><?= t('park_rules_h') ?></h3>
        <p><?= t('park_rules_p') ?></p>
      </div>

      <table class="price-table">
        <?php foreach ($d['monthly'] as $row): ?>
          <tr>
            <td><?= t($row['label']) ?></td>
            <td class="price-val">
              <?= e($row['price']) ?>
              <span class="unit-m"><?= t('park_unit_month') ?></span>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>

      <h2 class="section-h2"><?= t('park_spec_h') ?></h2>
      <p class="spec-sub"><?= t('park_spec_sub') ?></p>
      <table class="price-table">
        <?php foreach ($d['special'] as $row): ?>
          <tr>
            <td><?= t($row['label']) ?></td>
            <td class="price-val">
              <?= e($row['price']) ?>
              <span class="unit-m"><?= t('park_unit_month') ?></span>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>

      <div class="info-card">
        <h3><?= t('park_how_h') ?></h3>
        <p><?= t('park_how_p') ?></p>
      </div>
    </div>
  </section>

  <!-- FORM -->
  <section class="s-form" id="booking">
    <div class="container">
      <div class="form-wrap">
        <h2><?= t('park_form_h') ?></h2>
        <form id="infra-form"
              action="<?= url('php/telegram.php') ?>"
              method="POST"
              data-form-name="parking"
              data-thankyou="thank-you.html">

          <input type="hidden" name="utm_source"   id="utm_source"   value="">
          <input type="hidden" name="utm_medium"   id="utm_medium"   value="">
          <input type="hidden" name="utm_campaign" id="utm_campaign" value="">
          <input type="hidden" name="utm_term"     id="utm_term"     value="">
          <input type="hidden" name="utm_content"  id="utm_content"  value="">

          <div class="input-group">
            <select name="service" id="service-select">
              <?php foreach ($d['options'] as $opt): ?>
                <option value="<?= e($opt['value']) ?>"><?= t($opt['label']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="input-group">
            <input type="text" name="name" id="user-name"
                   placeholder="<?= e(t('park_name_ph')) ?>"
                   required>
          </div>

          <div class="input-group">
            <input type="tel" name="phone" id="user-phone"
                   value="+380" required maxlength="13">
          </div>

          <button type="submit" class="btn-submit" id="btn-send"><?= t('park_btn_send') ?></button>
          <div class="form-err" id="f-err" role="alert"></div>
        </form>
      </div>
    </div>
  </section>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <script>
    window.PARKING_I18N = {
      sending:  <?= json_encode(t('park_sending'),  JSON_UNESCAPED_UNICODE) ?>,
      errPhone: <?= json_encode(t('park_err_phone'),JSON_UNESCAPED_UNICODE) ?>,
      errSend:  <?= json_encode(t('park_err_send'), JSON_UNESCAPED_UNICODE) ?>
    };
  </script>
  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>
</body>
</html>