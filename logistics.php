<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'logistics';
$PAGE_TITLE = t('logi_page_title');
$PAGE_DESC  = t('logi_page_desc');
$PAGE_CSS   = ['logistics'];
$PAGE_JS    = ['main', 'logistics'];
$HEAD_JS    = ['utm'];
$PAGE_EXTERNAL_CSS = [
    'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
];

$d = require __DIR__ . '/includes/data/logistics.php';

require __DIR__ . '/includes/head.php';
?>
<body>
  <div class="bg-glow"></div>

  <?php require __DIR__ . '/includes/header.php'; ?>

  <!-- HERO -->
  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <span class="hero-tagline">INTERNATIONAL TRANSPORT HOLDING</span>
        <h1><?= t('logi_h1') ?></h1>

        <div class="calc-wrapper">
          <div class="calc-grid">
            <div>
              <label class="calc-label"><?= t('logi_label_from') ?></label>
              <select id="from" style="width:100%">
                <?php foreach ($d['cities'] as $c): ?>
                  <option value="<?= e($c['id']) ?>"><?= e($c[LANG]) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="calc-label"><?= t('logi_label_to') ?></label>
              <select id="to" style="width:100%">
                <?php foreach ($d['cities'] as $c): ?>
                  <option value="<?= e($c['id']) ?>"><?= e($c[LANG]) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <button class="btn-calc" id="btn-calc-text"><?= t('logi_btn_calc') ?></button>
          </div>

          <div id="res-block" class="calc-result">
            <div class="res-price">
              <span id="total">0</span> <span id="currency"><?= t('logi_currency') ?></span>
            </div>
            <p id="km" class="res-dist"></p>
            <a id="wa" href="#" target="_blank" rel="noopener" class="btn-calc"
               style="display:inline-block;text-decoration:none;padding:15px 30px;background:#25d366">
              <?= t('logi_wa_btn') ?>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- BENEFITS -->
  <section class="container section-padding">
    <h2 class="section-title"><?= t('logi_sec_benefits') ?></h2>
    <div class="benefits-grid">
      <?php foreach ($d['benefits'] as $b): ?>
        <div class="benefit-card">
          <h3><?= t($b['h']) ?></h3>
          <p><?= t($b['p']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- FLEET -->
  <section class="container section-padding">
    <h2 class="section-title"><?= t('logi_sec_fleet') ?></h2>
    <div class="fleet-grid">
      <?php foreach ($d['fleet'] as $f): ?>
        <div class="fleet-card">
          <div class="fleet-img"
               style="background-image:url('<?= url($f['img']) ?>')"></div>
          <div class="fleet-info">
            <h3><?= t($f['h']) ?></h3>
            <ul class="fleet-specs">
              <?php foreach ($f['specs'] as $s): ?>
                <li><?= t($s) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- REVIEWS -->
  <section class="container section-padding">
    <h2 class="section-title"><?= t('logi_sec_reviews') ?></h2>
    <div class="reviews-scroll">
      <div class="reviews-track">
        <?php
          $revHtml = '';
          foreach ($d['reviews'] as $r) {
              $revHtml .= '<div class="review-card"><p>«' . t($r['p']) . '»</p>'
                        . '<span class="review-author">' . e(t($r['a'])) . '</span></div>';
          }
          echo $revHtml . $revHtml;
        ?>
      </div>
    </div>
  </section>

  <!-- PARTNERS -->
  <section class="container section-padding">
    <h2 class="section-title"><?= t('logi_sec_partners') ?></h2>
    <div class="partners-grid">
      <?php foreach ($d['partners'] as $p): ?>
        <div class="partner-box">
          <img src="<?= url('img/public/logistic/partners/' . $p) ?>"
               alt="Partner" loading="lazy"
               onerror="this.parentElement.style.display='none'">
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- FORM -->
  <section class="s-feedback" id="feedback-form-section">
    <div class="container">
      <div class="feedback-card">
        <h3><?= t('logi_feedback_title') ?></h3>
        <div class="feedback-desc"><?= t('logi_feedback_desc') ?></div>

        <form id="callbackForm"
              action="<?= url('php/telegram.php') ?>"
              method="POST"
              data-form-name="logistics"
              data-thankyou="thank-you.html">

          <input type="hidden" name="utm_source"   id="utm_source"   value="">
          <input type="hidden" name="utm_medium"   id="utm_medium"   value="">
          <input type="hidden" name="utm_campaign" id="utm_campaign" value="">
          <input type="hidden" name="utm_term"     id="utm_term"     value="">
          <input type="hidden" name="utm_content"  id="utm_content"  value="">

          <div class="form-group">
            <label for="serviceSelect"><?= t('logi_service_label') ?> <span class="req">*</span></label>
            <select id="serviceSelect" name="service" required>
              <option value="" disabled selected><?= t('logi_service_choose') ?></option>
              <option value="Вантажні перевезення Україна"><?= t('logi_opt_ua') ?></option>
              <option value="Міжнародні вантажоперевезення"><?= t('logi_opt_int') ?></option>
            </select>
          </div>

          <div class="form-group">
            <label for="name"><?= t('logi_name_label') ?> <span class="req">*</span></label>
            <input type="text" id="name" name="name"
                   placeholder="<?= e(t('logi_name_ph')) ?>"
                   autocomplete="name">
            <div class="error-message" id="nameError"></div>
          </div>

          <div class="form-group">
            <label for="phone"><?= t('logi_phone_label') ?> <span class="req">*</span></label>
            <input type="tel" id="phone" name="phone"
                   placeholder="+380" maxlength="13" autocomplete="tel" value="+380">
            <div class="error-message" id="phoneError"></div>
          </div>

          <button type="submit" class="submit-feedback" id="submitBtnText">
            <?= t('logi_submit_btn') ?>
          </button>
          <div class="form-note"><?= t('logi_form_note') ?></div>
        </form>
      </div>
    </div>
  </section>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <script>
    window.LOGISTICS_CONF = {
      formName: 'logistics',
      thankyou: <?= json_encode(url('thank-you.html'), JSON_UNESCAPED_SLASHES) ?>,
      waPhone:  '380730779009'
    };
    window.LOGISTICS_I18N = {
      placeholder:   <?= json_encode(t('logi_placeholder'),    JSON_UNESCAPED_UNICODE) ?>,
      kmLabel:       <?= json_encode(t('logi_km_label'),       JSON_UNESCAPED_UNICODE) ?>,
      waPrefix:      <?= json_encode(t('logi_wa_prefix'),      JSON_UNESCAPED_UNICODE) ?>,
      sending:       <?= json_encode(t('logi_sending'),        JSON_UNESCAPED_UNICODE) ?>,
      errName:       <?= json_encode(t('logi_err_name'),       JSON_UNESCAPED_UNICODE) ?>,
      errNameDigits: <?= json_encode(t('logi_err_name_digits'),JSON_UNESCAPED_UNICODE) ?>,
      errPhone:      <?= json_encode(t('logi_err_phone'),      JSON_UNESCAPED_UNICODE) ?>,
      errPhoneFormat:<?= json_encode(t('logi_err_phone_fmt'),  JSON_UNESCAPED_UNICODE) ?>,
      errSendFailed: <?= json_encode(t('logi_err_send'),       JSON_UNESCAPED_UNICODE) ?>
    };
  </script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>
</body>
</html>