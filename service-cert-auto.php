<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'service-cert-auto';
$PAGE_TITLE = t('cert_page_title');
$PAGE_DESC  = t('cert_page_desc');
$PAGE_CSS   = ['service-cert-auto'];
$PAGE_JS    = ['main', 'service-cert-auto'];
$HEAD_JS    = ['utm'];

$d = require __DIR__ . '/includes/data/service-cert-auto.php';

require __DIR__ . '/includes/head.php';
?>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <!-- HERO -->
  <section class="hero-sub">
    <div class="container">
      <a href="<?= url('hub.php') ?>" class="btn-back-link"><?= t('cert_back') ?></a>
      <h1 class="hero-title"><?= t('cert_h1') ?></h1>
      <div class="line"></div>
    </div>
  </section>

  <!-- CAR TYPES -->
  <section class="s-car-types">
    <div class="container">
      <h2><?= t('cert_types_title') ?></h2>
      <div class="line center"></div>
      <div class="car-grid">
        <?php foreach ($d['car_types'] as $c): ?>
          <div class="car-card <?= e($c['class']) ?>">
            <div class="car-info">
              <h3><?= t($c['title']) ?></h3>
              <p><?= t($c['price']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- REQUIREMENTS -->
  <section class="s-requirements">
    <div class="container">
      <h2><?= t('cert_req_title') ?></h2>
      <div class="line center"></div>
      <div class="req-grid">
        <?php foreach ($d['requirements'] as $r): ?>
          <div class="req-item">
            <h4><?= t($r['h']) ?></h4>
            <p><?= t($r['p']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- DOCS -->
  <section class="s-docs">
    <div class="container">
      <div style="max-width:800px;margin:0 auto">
        <h2><?= t('cert_docs_title') ?></h2>
        <div class="line center"></div>
        <ul class="docs-list">
          <?php foreach ($d['docs'] as $k): ?>
            <li><?= t($k) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>

  <!-- FORM -->
  <section class="s-feedback" id="feedback-form-section">
    <div class="container">
      <div class="feedback-card">
        <h3><?= t('cert_feedback_title') ?></h3>
        <div class="feedback-desc"><?= t('cert_feedback_desc') ?></div>

        <form id="callbackForm"
              action="<?= url('php/telegram.php') ?>"
              method="POST"
              data-form-name="cert_auto"
              data-thankyou="thank-you.html">

          <input type="hidden" name="utm_source"   id="utm_source"   value="">
          <input type="hidden" name="utm_medium"   id="utm_medium"   value="">
          <input type="hidden" name="utm_campaign" id="utm_campaign" value="">
          <input type="hidden" name="utm_term"     id="utm_term"     value="">
          <input type="hidden" name="utm_content"  id="utm_content"  value="">

          <div class="form-group">
            <label for="serviceSelect"><?= t('cert_service_label') ?> <span class="req">*</span></label>
            <select id="serviceSelect" name="service" required>
              <option value="" disabled selected><?= t('cert_service_choose') ?></option>
              <?php foreach ($d['options'] as $opt): ?>
                <option value="<?= e($opt['value']) ?>"><?= t($opt['label']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="name"><?= t('cert_name_label') ?> <span class="req">*</span></label>
            <input type="text" id="name" name="name"
                   placeholder="<?= e(t('cert_name_ph')) ?>"
                   autocomplete="name">
            <div class="error-message" id="nameError"></div>
          </div>

          <div class="form-group">
            <label for="phone"><?= t('cert_phone_label') ?> <span class="req">*</span></label>
            <input type="tel" id="phone" name="phone"
                   placeholder="+380" maxlength="13" autocomplete="tel" value="+380">
            <div class="error-message" id="phoneError"></div>
          </div>

          <button type="submit" class="submit-feedback" id="submitBtnText">
            <?= t('cert_submit_btn') ?>
          </button>
          <div class="form-note"><?= t('cert_form_note') ?></div>
        </form>
      </div>
    </div>
  </section>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <script>
    window.CERT_AUTO_I18N = {
      sending:        <?= json_encode(t('cert_sending'),        JSON_UNESCAPED_UNICODE) ?>,
      errName:        <?= json_encode(t('cert_err_name'),       JSON_UNESCAPED_UNICODE) ?>,
      errNameDigits:  <?= json_encode(t('cert_err_name_digits'),JSON_UNESCAPED_UNICODE) ?>,
      errPhone:       <?= json_encode(t('cert_err_phone'),      JSON_UNESCAPED_UNICODE) ?>,
      errPhoneFormat: <?= json_encode(t('cert_err_phone_fmt'),  JSON_UNESCAPED_UNICODE) ?>,
      errSendFailed:  <?= json_encode(t('cert_err_send'),       JSON_UNESCAPED_UNICODE) ?>
    };
  </script>
  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>

  <script src="https://widgets.binotel.com/getcall/widgets/o2ewjg8bzd7eiw86yqnk.js" async></script>
</body>
</html>