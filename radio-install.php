<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'radio-install';
$PAGE_TITLE = t('rad_page_title');
$PAGE_DESC  = t('rad_page_desc');
$PAGE_CSS   = ['radio-install'];
$PAGE_JS    = ['main', 'radio-install'];
$HEAD_JS    = ['utm'];

$d = require __DIR__ . '/includes/data/radio-install.php';

require __DIR__ . '/includes/head.php';
?>
<body>
  <noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N89VVZFR"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
  </noscript>

  <?php require __DIR__ . '/includes/header.php'; ?>

  <!-- HERO -->
  <section class="hero-sub">
    <div class="container">
      <a href="<?= url('hub.php') ?>" class="btn-back-link"><?= t('rad_back') ?></a>
      <h1 class="hero-title"><?= t('rad_h1') ?></h1>
      <div class="line"></div>
    </div>
  </section>

  <!-- EXP -->
  <section class="s-experience">
    <div class="container">
      <div class="exp-grid">
        <div class="exp-item"><b>10+</b><span><?= t('rad_exp1') ?></span></div>
        <div class="exp-item"><b>500+</b><span><?= t('rad_exp2') ?></span></div>
        <div class="exp-item"><b>100%</b><span><?= t('rad_exp3') ?></span></div>
      </div>
    </div>
  </section>

  <!-- PRICES -->
  <section class="s-prices">
    <div class="container">
      <h2><?= t('rad_price_title') ?></h2>
      <table class="price-table">
        <?php foreach ($d['prices'] as $row): ?>
          <tr>
            <td><?= t($row[0]) ?></td>
            <td><?= t($row[1]) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </section>

  <!-- WORK -->
  <section class="s-work">
    <div class="container">
      <h2><?= t('rad_work_title') ?></h2>
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

  <!-- FEEDBACK -->
  <section class="s-feedback" id="feedback-form-section">
    <div class="container">
      <div class="feedback-card">
        <h3><?= t('rad_feedback_title') ?></h3>
        <div class="feedback-desc"><?= t('rad_feedback_desc') ?></div>

        <form id="callbackForm"
              action="<?= url('php/telegram.php') ?>"
              method="POST"
              data-form-name="radio_install"
              data-thankyou="thank-you.html">

          <input type="hidden" name="utm_source"   id="utm_source"   value="">
          <input type="hidden" name="utm_medium"   id="utm_medium"   value="">
          <input type="hidden" name="utm_campaign" id="utm_campaign" value="">
          <input type="hidden" name="utm_term"     id="utm_term"     value="">
          <input type="hidden" name="utm_content"  id="utm_content"  value="">

          <div class="form-group">
            <label for="serviceSelect"><?= t('rad_service_label') ?> <span class="req">*</span></label>
            <select id="serviceSelect" name="service" required>
              <option value="" disabled selected><?= t('rad_service_choose') ?></option>
              <?php foreach ($d['options'] as $opt): ?>
                <option value="<?= e($opt['value_uk']) ?>"><?= t($opt['label']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="name"><?= t('rad_name_label') ?> <span class="req">*</span></label>
            <input type="text" id="name" name="name"
                   placeholder="<?= e(t('rad_name_ph')) ?>"
                   autocomplete="name">
            <div class="error-message" id="nameError"></div>
          </div>

          <div class="form-group">
            <label for="phone"><?= t('rad_phone_label') ?> <span class="req">*</span></label>
            <input type="tel" id="phone" name="phone"
                   placeholder="+380" maxlength="13" autocomplete="tel" value="+380">
            <div class="error-message" id="phoneError"></div>
          </div>

          <button type="submit" class="submit-feedback" id="submitBtnText">
            <?= t('rad_submit_btn') ?>
          </button>
          <div class="form-note"><?= t('rad_form_note') ?></div>
        </form>
      </div>
    </div>
  </section>

  <a href="tel:+380660339009" class="fixed-call-btn" aria-label="Зателефонувати">
    <svg viewBox="0 0 24 24">
      <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
    </svg>
  </a>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <script>
    window.RADIO_INSTALL_I18N = {
      sending:          <?= json_encode(t('rad_sending'),          JSON_UNESCAPED_UNICODE) ?>,
      errName:          <?= json_encode(t('rad_err_name'),         JSON_UNESCAPED_UNICODE) ?>,
      errNameDigits:    <?= json_encode(t('rad_err_name_digits'),  JSON_UNESCAPED_UNICODE) ?>,
      errPhone:         <?= json_encode(t('rad_err_phone'),        JSON_UNESCAPED_UNICODE) ?>,
      errPhoneFormat:   <?= json_encode(t('rad_err_phone_fmt'),    JSON_UNESCAPED_UNICODE) ?>,
      errSendFailed:    <?= json_encode(t('rad_err_send'),         JSON_UNESCAPED_UNICODE) ?>
    };
  </script>
  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>
</body>
</html>