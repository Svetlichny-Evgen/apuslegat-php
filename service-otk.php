<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'service-otk';
$PAGE_TITLE = t('otk_page_title');
$PAGE_DESC  = t('otk_page_desc');
$PAGE_CSS   = ['service-otk'];
$PAGE_JS    = ['main', 'service-otk'];
$HEAD_JS    = ['utm'];

$pricesData = require __DIR__ . '/includes/data/otk-prices.php';
$prices     = $pricesData[LANG]           ?? $pricesData['uk'];
$options    = $pricesData['options'][LANG] ?? $pricesData['options']['uk'];

require __DIR__ . '/includes/head.php';
?>
<body>
  <noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N89VVZFR"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
  </noscript>

  <?php require __DIR__ . '/includes/header.php'; ?>

  <!-- HERO -->
  <section class="hero">
    <svg class="wm"><use href="#lg" /></svg>
    <div class="container">
      <a href="<?= url('hub.php') ?>" class="back"><?= t('otk_back') ?></a>
      <span class="tag"><?= t('otk_tag') ?></span>
      <h1><?= t('otk_h1') ?></h1>
      <p class="lead"><?= t('otk_lead') ?></p>
      <div class="btns">
        <a href="#booking" class="btn red"><?= t('otk_bm') ?></a>
        <a href="tel:+380660339009" class="btn mono">+380 66 033 90 09</a>
      </div>
    </div>
  </section>

  <!-- КАТЕГОРИИ -->
  <section class="band light">
    <div class="container sec">
      <h2 class="sec-title"><?= t('otk_ct') ?></h2>
      <div class="cats">
        <div class="cat"><i>M</i><h3><?= t('otk_cm') ?></h3><p><?= t('otk_cmp') ?></p></div>
        <div class="cat"><i>N</i><h3><?= t('otk_cn') ?></h3><p><?= t('otk_cnp') ?></p></div>
        <div class="cat"><i>O</i><h3><?= t('otk_co') ?></h3><p><?= t('otk_cop') ?></p></div>
      </div>
    </div>
  </section>

  <!-- ПРАЙС -->
  <section class="band mid">
    <div class="container sec">
      <h2 class="sec-title"><?= t('otk_pt') ?></h2>
      <table class="ptable">
        <?php foreach ($prices as $row): ?>
          <tr>
            <td><span class="ctag"><?= e($row[0]) ?></span><?= e($row[1]) ?></td>
            <td class="pv"><?= e($row[2]) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </section>

  <!-- ПАРТНЁРЫ -->
  <section class="band light2">
    <div class="container sec">
      <h2 class="sec-title"><?= t('otk_wt') ?></h2>
      <p class="intro"><?= t('otk_wp') ?></p>
      <div class="feat">
        <div class="fc1"><i>01</i><h3><?= t('otk_f1') ?></h3><p><?= t('otk_f1p') ?></p></div>
        <div class="fc1"><i>02</i><h3><?= t('otk_f2') ?></h3><p><?= t('otk_f2p') ?></p></div>
        <div class="fc1"><i>03</i><h3><?= t('otk_f3') ?></h3><p><?= t('otk_f3p') ?></p></div>
      </div>
    </div>
  </section>

  <!-- ЗАПИСЬ -->
  <section class="band book" id="booking">
    <div class="container sec">
      <div class="bgrid">
        <div class="info">
          <h2 class="sec-title"><?= t('otk_bt') ?></h2>
          <ol class="steps">
            <li><i>01</i><span><?= t('otk_s1') ?></span></li>
            <li><i>02</i><span><?= t('otk_s2') ?></span></li>
            <li><i>03</i><span><?= t('otk_s3') ?></span></li>
          </ol>
          <p><b><?= t('otk_ih') ?></b> <?= t('otk_ihv') ?></p>
          <p><b><?= t('otk_ia') ?></b> <?= t('otk_iav') ?></p>
          <p>
            <b class="mono">Tel:</b>
            <a href="tel:+380660339009">+380 66 033 90 09</a> ·
            <a href="tel:+380630339009">+380 63 033 90 09</a>
          </p>
        </div>
        <div class="form-wrap">
          <h3><?= t('otk_fh') ?></h3>
          <form id="otk-form"
                action="<?= url('php/telegram.php') ?>"
                method="post"
                data-form-name="otk"
                data-thankyou="thank-you.html">
            <input type="hidden" name="utm_source"   id="utm_source"   value="">
            <input type="hidden" name="utm_medium"   id="utm_medium"   value="">
            <input type="hidden" name="utm_campaign" id="utm_campaign" value="">
            <input type="hidden" name="utm_term"     id="utm_term"     value="">
            <input type="hidden" name="utm_content"  id="utm_content"  value="">

            <div class="input-group">
              <select id="f-select" name="service">
                <?php foreach ($options as $opt): ?>
                  <option value="<?= e(t('otk_form_prefix') . $opt) ?>"><?= e($opt) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="input-group">
              <input type="text" id="f-name" name="name"
                     placeholder="<?= e(t('otk_ph_name')) ?>"
                     required
                     oninput="this.value = this.value.replace(/[0-9]/g, '')">
            </div>

            <div class="input-group">
              <input type="tel" id="f-phone" name="phone"
                     value="+380" required maxlength="13">
            </div>

            <button type="submit" class="btn-submit" id="f-btn"><?= t('otk_fb') ?></button>
            <div class="err" id="f-err" role="alert"></div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <script>
    window.OTK_I18N = {
      sending: <?= json_encode(t('otk_sending'), JSON_UNESCAPED_UNICODE) ?>,
      fail:    <?= json_encode(t('otk_fail'),    JSON_UNESCAPED_UNICODE) ?>
    };
  </script>
  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>

  <script src="https://widgets.binotel.com/getcall/widgets/0305ke8ilkfx9btr2c3v.js" async></script>
</body>
</html>