<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'service-extra';
$PAGE_TITLE = t('extra_page_title');
$PAGE_DESC  = t('extra_page_desc');
$PAGE_CSS   = ['service-extra'];
$PAGE_JS    = ['main', 'service-extra'];
$HEAD_JS    = ['utm'];

$d = require __DIR__ . '/includes/data/service-extra.php';

require __DIR__ . '/includes/head.php';
?>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <!-- HERO -->
  <section class="hero-extra">
    <div class="container">
      <a href="<?= url('hub.php') ?>" class="btn-back"><?= t('extra_back') ?></a><br>
      <h1 class="hero-title"><?= t('extra_h1') ?></h1>
    </div>
  </section>

  <!-- CARDS -->
  <section class="s-extra">
    <div class="container">
      <div class="extra-grid">
        <?php foreach ($d['cards'] as $card):
          $bg = $card['is_local'] ? url($card['img']) : $card['img'];
        ?>
          <div class="extra-card">
            <div class="extra-img" style="background-image:url('<?= e($bg) ?>')"></div>
            <div class="extra-content">
              <h3><?= t($card['title']) ?></h3>
              <p><?= t($card['desc']) ?></p>
              <?php if (!empty($card['list'])): ?>
                <ul class="extra-list">
                  <?php foreach ($card['list'] as $k): ?>
                    <li><?= t($k) ?></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
              <?php if (!empty($card['price'])): ?>
                <p class="extra-price"><?= t($card['price']) ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- FORM -->
  <section class="s-form" id="contact">
    <div class="container">
      <div class="form-wrap">
        <h2><?= t('extra_form_title') ?></h2>
        <form id="extra-form"
              action="<?= url('php/telegram.php') ?>"
              method="POST"
              data-form-name="service_extra"
              data-thankyou="thank-you.html">

          <input type="hidden" name="utm_source"   id="utm_source"   value="">
          <input type="hidden" name="utm_medium"   id="utm_medium"   value="">
          <input type="hidden" name="utm_campaign" id="utm_campaign" value="">
          <input type="hidden" name="utm_term"     id="utm_term"     value="">
          <input type="hidden" name="utm_content"  id="utm_content"  value="">

          <div class="input-group">
            <select id="f-service" name="service">
              <?php foreach ($d['options'] as $opt): ?>
                <option value="<?= e($opt['value']) ?>"><?= t($opt['label']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="input-group">
            <input type="text" name="name" id="f-name"
                   placeholder="<?= e(t('extra_name_ph')) ?>"
                   required>
          </div>

          <div class="input-group">
            <input type="tel" name="phone" id="f-phone"
                   placeholder="+380" required maxlength="13">
          </div>

          <button type="submit" class="btn-send" id="f-btn"><?= t('extra_btn_send') ?></button>
          <div class="form-err" id="f-err" role="alert"></div>
        </form>
      </div>
    </div>
  </section>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <script>
    window.EXTRA_I18N = {
      sending:  <?= json_encode(t('extra_sending'),     JSON_UNESCAPED_UNICODE) ?>,
      errPhone: <?= json_encode(t('extra_err_phone'),   JSON_UNESCAPED_UNICODE) ?>,
      errSend:  <?= json_encode(t('extra_err_send'),    JSON_UNESCAPED_UNICODE) ?>
    };
  </script>
  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>

  <script src="https://widgets.binotel.com/getcall/widgets/1l7bnrr43nehogjf5ueh.js" async></script>
</body>
</html>