<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'service-tacho';
$PAGE_TITLE = t('tacho_page_title');
$PAGE_DESC  = t('tacho_page_desc');
$PAGE_CSS   = ['service-tacho'];
$PAGE_JS    = ['main', 'service-tacho'];
$HEAD_JS    = ['utm'];

$tachoPage = require __DIR__ . '/includes/data/tacho-page.php';
$t         = $tachoPage[LANG]     ?? $tachoPage['uk'];
$uk        = $tachoPage['uk'];
$brands    = $tachoPage['brands'];

/* Первый ряд прайса — feat-карточка, остальное — таблица */
$featPrice  = $t['prices'][0][0] ?? '';
$tableRows  = array_slice($t['prices'], 1);

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
    <iframe class="hero-video"
      src="https://www.youtube.com/embed/ccTuiy8KCDc?autoplay=1&mute=1&controls=0&loop=1&playlist=ccTuiy8KCDc"
      frameborder="0" tabindex="-1" aria-hidden="true" title=""></iframe>
    <div class="hero-shade"></div>
    <div class="container">
      <a href="<?= url('hub.php') ?>" class="back"><?= t('tacho_back') ?></a>
      <span class="tag">Apus Legat</span>
      <h1><?= t('tacho_h1') ?></h1>
      <p class="lead"><?= t('tacho_lead') ?></p>
      <div class="btns">
        <a href="#feedback-form-section" class="btn red"><?= t('tacho_bo') ?></a>
        <a href="tel:+380660339009" class="btn mono">+380 66 033 90 09</a>
      </div>
    </div>
  </section>

  <!-- FACTS -->
  <div class="facts">
    <div class="fact"><strong>500+</strong><span><?= t('tacho_x1') ?></span></div>
    <div class="fact">
      <strong>15 <small style="font-size:.5em"><?= t('tacho_xm') ?></small></strong>
      <span><?= t('tacho_x2') ?></span>
    </div>
    <div class="fact"><strong>ЄСТР</strong><span><?= t('tacho_x3') ?></span></div>
  </div>

  <!-- ПОСЛУГИ ТА ЦІНИ -->
  <section class="band light">
    <div class="container sec">
      <h2 class="sec-title"><?= t('tacho_pt') ?></h2>

      <div class="feat">
        <strong><?= e($featPrice) ?></strong>
        <a href="<?= url('tacho-install.php') ?>" class="btn red"><?= e($t['more']) ?></a>
      </div>

      <table class="ptable">
        <?php foreach ($tableRows as $row): ?>
          <tr><td><?= e($row[0]) ?></td><td><?= e($row[1]) ?></td></tr>
        <?php endforeach; ?>
      </table>
    </div>
  </section>

  <!-- БРЕНДЫ -->
  <section class="band mid">
    <div class="brands-wrap">
      <div class="mq">
        <div class="mq-track">
          <?php
            /* два круга подряд для бесшовной прокрутки */
            $allBrands = array_merge($brands, $brands, $brands, $brands);
            foreach ($allBrands as $b):
          ?>
            <div class="brand"><?= e($b) ?></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ВІДГУКИ -->
  <section class="band light2">
    <div class="container sec" style="padding-bottom:40px">
      <h2 class="sec-title"><?= t('tacho_rt') ?></h2>
    </div>
    <div class="mq">
      <div class="rev-track">
        <?php
          /* повторы для бесшовной карусели: 2 полных круга */
          $revs = $t['reviews'];
          $revsLoop = array_merge($revs, $revs, $revs, $revs);
          foreach ($revsLoop as $r):
        ?>
          <div class="rev">
            <div class="st">★★★★★</div>
            <b><?= e($r['name']) ?></b>
            <p>“<?= e($r['text']) ?>”</p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ФОРМА -->
  <section class="band book" id="feedback-form-section">
    <div class="container sec">
      <div class="bgrid">
        <div class="info">
          <h2 class="sec-title"><?= t('tacho_bt') ?></h2>
          <p><?= t('tacho_bd') ?></p>
          <ol class="steps">
            <li><i>01</i><span><?= t('tacho_s1') ?></span></li>
            <li><i>02</i><span><?= t('tacho_s2') ?></span></li>
            <li><i>03</i><span><?= t('tacho_s3') ?></span></li>
          </ol>
          <div class="contacts">
            <p><b><?= t('tacho_ih') ?></b> <?= t('tacho_ihv') ?></p>
            <p><b><?= t('tacho_ia') ?></b> <?= t('tacho_iav') ?></p>
            <p>
              <b class="mono">Tel:</b>
              <a href="tel:+380660339009">+380 66 033 90 09</a> ·
              <a href="tel:+380630339009">+380 63 033 90 09</a>
            </p>
          </div>
        </div>

        <div class="form-wrap">
          <h3><?= t('tacho_ft') ?></h3>
          <form id="callbackForm"
                action="<?= url('php/telegram.php') ?>"
                method="POST"
                data-form-name="tacho_service"
                data-thankyou="thank-you.html">

            <input type="hidden" name="utm_source"   id="utm_source"   value="">
            <input type="hidden" name="utm_medium"   id="utm_medium"   value="">
            <input type="hidden" name="utm_campaign" id="utm_campaign" value="">
            <input type="hidden" name="utm_term"     id="utm_term"     value="">
            <input type="hidden" name="utm_content"  id="utm_content"  value="">

            <div class="fg">
              <label for="serviceSelect">
                <span><?= t('tacho_sl') ?></span> <span class="req">*</span>
              </label>
              <select id="serviceSelect" name="service" required>
                <option value="" disabled selected><?= e($t['choose']) ?></option>
                <?php foreach ($uk['options'] as $i => $value): ?>
                  <option value="<?= e($value) ?>"><?= e($t['options'][$i] ?? $value) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="fg">
              <label for="name">
                <span><?= t('tacho_nl') ?></span> <span class="req">*</span>
              </label>
              <input type="text" id="name" name="name"
                     placeholder="<?= e($t['namePh']) ?>"
                     autocomplete="name">
              <div class="error-message" id="nameError"></div>
            </div>

            <div class="fg">
              <label for="phone">
                <span><?= t('tacho_pl') ?></span> <span class="req">*</span>
              </label>
              <input type="tel" id="phone" name="phone"
                     placeholder="+380660339009"
                     maxlength="13"
                     autocomplete="tel"
                     value="+380">
              <div class="error-message" id="phoneError"></div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtnText"><?= t('tacho_sb') ?></button>
            <div class="err" id="f-err" role="alert"></div>
            <div class="form-note"><?= t('tacho_fn2') ?></div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <script>
    window.TACHO_I18N = {
      sending: <?= json_encode($t['sending'], JSON_UNESCAPED_UNICODE) ?>,
      fail:    <?= json_encode($t['fail'],    JSON_UNESCAPED_UNICODE) ?>,
      eName:   <?= json_encode($t['eName'],   JSON_UNESCAPED_UNICODE) ?>,
      eDigits: <?= json_encode($t['eDigits'], JSON_UNESCAPED_UNICODE) ?>,
      ePhone:  <?= json_encode($t['ePhone'],  JSON_UNESCAPED_UNICODE) ?>,
      ePhone2: <?= json_encode($t['ePhone2'], JSON_UNESCAPED_UNICODE) ?>
    };
  </script>
  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>

  <script src="https://widgets.binotel.com/getcall/widgets/65oqsjw02q6e9f2q3hf4.js" async></script>
</body>
</html>