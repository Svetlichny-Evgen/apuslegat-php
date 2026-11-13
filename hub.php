<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'hub';
$PAGE_TITLE = 'Усі послуги — APUS LEGAT, Харків';
$PAGE_DESC  = 'Усі послуги APUS LEGAT у Харкові: ОТК, тахографи, сертифікація авто та цистерн ADR, документи, логістика, стоянка, магазин.';
$PAGE_CSS   = ['hub'];
$PAGE_JS    = ['main', 'hub'];

$services = require __DIR__ . '/includes/data/services.php';
$filters  = require __DIR__ . '/includes/data/hub-filters.php';

require __DIR__ . '/includes/head.php';
?>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <!-- HERO -->
  <section class="hero">
    <svg class="wm"><use href="#lg" /></svg>
    <div class="container">
      <span class="tag"><?= t('hub_tag') ?></span>
      <h1><?= t('hub_h1') ?></h1>
      <p class="lead"><?= t('hub_lead') ?></p>
    </div>
  </section>

  <!-- FILTERS -->
  <div class="needs">
    <div class="container" id="chips">
      <span class="nl"><?= t('hub_nlabel') ?></span>
      <?php foreach ($filters as $i => $c): ?>
        <button class="chip <?= $i === 0 ? 'on' : '' ?>" data-f="<?= e($c['f']) ?>"><?= t($c['key']) ?></button>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- GRID -->
  <section class="grid-wrap">
    <div class="container">
      <div class="found">
        <span><?= t('hub_found') ?></span> <b id="cnt"><?= count($services) ?></b>
      </div>
      <div class="grid" id="grid">
        <?php foreach ($services as $s): ?>
          <a class="tile" href="<?= url($s['file'] . '.php') ?>" data-n="<?= e($s['n']) ?>">
            <i><?= e($s['num']) ?></i>
            <h3><?= t($s['t']) ?></h3>
            <p><?= t($s['d']) ?></p>
            <div class="tf">
              <?php if (!empty($s['p'])): ?>
                <em><?= t($s['p']) ?></em>
              <?php endif; ?>
              <span class="go">→</span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="svc">
    <div class="container">
      <h2><?= t('ct') ?></h2>
      <p><?= t('cp') ?></p>
      <div class="btns">
        <a class="btn red mono" href="tel:+380660339009">+380 66 033 90 09</a>
        <a class="btn mono" href="tel:+380630339009">+380 63 033 90 09</a>
        <a class="btn" href="<?= url('contacts.php') ?>"><?= t('b3b') ?></a>
      </div>
    </div>
  </section>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>

  <script src="https://widgets.binotel.com/getcall/widgets/wmdr0ga4o26m9c3nfz58.js" async></script>
</body>
</html>