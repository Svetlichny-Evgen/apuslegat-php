<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'service-cert-tanks';
$PAGE_TITLE = t('tanks_page_title');
$PAGE_DESC  = t('tanks_page_desc');
$PAGE_CSS   = ['service-cert-tanks'];
$PAGE_JS    = ['main', 'service-cert-tanks'];
$HEAD_JS    = ['utm'];

$d = require __DIR__ . '/includes/data/service-cert-tanks.php';

require __DIR__ . '/includes/head.php';
?>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <!-- HERO ADR -->
  <section class="hero-adr">
    <div class="container hero-flex">
      <div class="hero-content">
        <a href="<?= url('hub.php') ?>" class="btn-back-link"><?= t('tanks_back') ?></a>
        <h1 class="hero-title"><?= t('tanks_h1') ?></h1>
        <p><?= t('tanks_lead') ?></p>
        <div class="line"></div>
        <a href="#feedback-form-section" class="btn-hero"><?= t('tanks_btn') ?></a>
      </div>
      <div class="adr-diamond">
        <div class="adr-diamond-content">
          ADR<span><?= t('tanks_adr_span') ?></span>
        </div>
      </div>
    </div>
  </section>

  <!-- METHODS -->
  <section class="s-methods">
    <div class="container">
      <h2><?= t('tanks_methods_title') ?></h2>
      <div class="line center"></div>
      <div class="methods-grid">
        <?php foreach ($d['methods'] as $m): ?>
          <div class="method-card">
            <h3><?= t($m['t']) ?></h3>
            <p><?= t($m['d']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- GALLERY -->
  <section class="s-gallery">
    <div class="container gallery-grid">
      <?php foreach ($d['gallery'] as $img): ?>
        <div class="gallery-item">
          <img src="<?= url($img['src']) ?>" alt="<?= e($img['alt']) ?>" loading="lazy">
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- DETAILS -->
  <section class="s-adr-details">
    <div class="container details-flex">
      <div class="details-box">
        <h2><?= t('tanks_types_title') ?></h2>
        <div class="line"></div>
        <ul class="adr-list">
          <?php foreach ($d['types'] as $k): ?>
            <li><?= t($k) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="details-box">
        <h2><?= t('tanks_results_title') ?></h2>
        <div class="line"></div>
        <ul class="adr-list">
          <?php foreach ($d['results'] as $k): ?>
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
        <h3><?= t('tanks_feedback_title') ?></h3>
        <div class="feedback-desc"><?= t('tanks_feedback_desc') ?></div>

        <form id="callbackForm"
              action="<?= url('php/telegram.php') ?>"
              method="POST"
              data-form-name="cert_tanks"
              data-thankyou="thank-you.html">

          <input type="hidden" name="utm_source"   id="utm_source"   value="">
          <input type="hidden" name="utm_medium"   id="utm_medium"   value="">
          <input type="hidden" name="utm_campaign" id="utm_campaign" value="">
          <input type="hidden" name="utm_term"     id="utm_term"     value="">
          <input type="hidden" name="utm_content"  id="utm_content"  value="">

          <div class="form-group">
            <label for="serviceSelect"><?= t('tanks_service_label') ?> <span class="req">*</span></label>
            <select id="serviceSelect" name="service" required>
              <option value="" disabled selected><?= t('tanks_service_choose') ?></option>
              <?php foreach ($d['options'] as $opt): ?>
                <option value="<?= e($opt['value']) ?>"><?= t($opt['label']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="name"><?= t('tanks_name_label') ?> <span class="req">*</span></label>
            <input type="text" id="name" name="name"
                   placeholder="<?= e(t('tanks_name_ph')) ?>"
                   autocomplete="name">
            <div class="error-message" id="nameError"></div>
          </div>

          <div class="form-group">
            <label for="phone"><?= t('tanks_phone_label') ?> <span class="req">*</span></label>
            <input type="tel" id="phone" name="phone"
                   placeholder="+380" maxlength="13" autocomplete="tel" value="+380">
            <div class="error-message" id="phoneError"></div>
          </div>

          <button type="submit" class="submit-feedback" id="submitBtnText">
            <?= t('tanks_submit_btn') ?>
          </button>
          <div class="form-note"><?= t('tanks_form_note') ?></div>
        </form>
      </div>
    </div>
  </section>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <script>
    window.CERT_TANKS_I18N = {
      sending:        <?= json_encode(t('tanks_sending'),        JSON_UNESCAPED_UNICODE) ?>,
      errName:        <?= json_encode(t('tanks_err_name'),       JSON_UNESCAPED_UNICODE) ?>,
      errNameDigits:  <?= json_encode(t('tanks_err_name_digits'),JSON_UNESCAPED_UNICODE) ?>,
      errPhone:       <?= json_encode(t('tanks_err_phone'),      JSON_UNESCAPED_UNICODE) ?>,
      errPhoneFormat: <?= json_encode(t('tanks_err_phone_fmt'),  JSON_UNESCAPED_UNICODE) ?>,
      errSendFailed:  <?= json_encode(t('tanks_err_send'),       JSON_UNESCAPED_UNICODE) ?>
    };
  </script>
  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>

  <script src="https://widgets.binotel.com/getcall/widgets/iwz8t4orpmjhgay4s2g5.js" async></script>
</body>
</html>