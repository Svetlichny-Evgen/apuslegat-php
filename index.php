<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'home';
$PAGE_TITLE = 'APUS LEGAT — Усе для перевізника в одному дворі, Харків';
$PAGE_DESC  = 'Тахографи, ОТК, сертифікація авто та цистерн ADR, логістика, паркінг і документи для перевізників у Харкові.';
$PAGE_CSS   = ['home'];
$PAGE_JS    = ['main', 'home'];

require __DIR__ . '/includes/head.php';
?>
<body>
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N89VVZFR"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

  <?php require __DIR__ . '/includes/header.php'; ?>

  <!-- HERO -->
  <section class="hero">
    <svg class="wm"><use href="#lg" /></svg>
    <div class="container">
      <div>
        <span class="tag"><?= t('tag') ?></span>
        <h1><?= t('h1') ?></h1>
        <p class="lead"><?= t('lead') ?></p>
        <div class="btns">
          <a href="#about" class="btn red"><?= t('bh1') ?></a>
          <a href="<?= url('contacts.php') ?>" class="btn"><?= t('bh2') ?></a>
        </div>
      </div>
      <div class="tacho" id="tacho">
        <small><?= t('tk') ?></small>
        <div class="lcd">
          <div class="time" id="clk">--:--</div>
          <div class="row">
            <span id="dt">--.--.----</span><span>KHARKIV</span>
          </div>
        </div>
        <div class="status">
          <span class="dot"></span><span id="stt">…</span>
        </div>
        <a class="ph mono" href="tel:+380660339009">+380 66 033 90 09</a>
      </div>
    </div>
  </section>

  <!-- STATS -->
  <div class="stats">
    <div class="stat"><strong>10+</strong><span><?= t('s1') ?></span></div>
    <div class="stat"><strong>500+</strong><span><?= t('s2') ?></span></div>
    <div class="stat"><strong>24/7</strong><span><?= t('s3') ?></span></div>
    <div class="stat">
      <strong>15 <small style="font-size:.45em"><?= t('s4m') ?></small></strong>
      <span><?= t('s4') ?></span>
    </div>
  </div>

  <!-- ABOUT -->
  <section class="band light" id="about">
    <div class="container sec">
      <h2 class="sec-title"><?= t('wt') ?></h2>
      <div class="about">
        <div>
          <p><?= t('w1') ?></p>
          <p><?= t('w2') ?></p>
          <p><?= t('w3') ?></p>
          <a class="btn" href="<?= url('about.php') ?>"><?= t('wb') ?></a>
        </div>
        <div class="fleet">
          <div><img src="<?= url('img/public/logistic/auto/T520.png') ?>" alt="Renault T520">RENAULT T520 · EURO 6</div>
          <div><img src="<?= url('img/public/logistic/auto/T480.png') ?>" alt="Renault T480">RENAULT T480</div>
          <div><img src="<?= url('img/public/logistic/auto/sprinter.png') ?>" alt="Mercedes Sprinter">MERCEDES SPRINTER</div>
        </div>
      </div>
    </div>
  </section>

  <!-- SERVICES -->
  <section class="band mid">
    <div class="container sec">
      <h2 class="sec-title"><?= t('dt') ?></h2>
      <div class="do">
        <div class="card"><i>01</i><h3><?= t('d1') ?></h3><p><?= t('d1p') ?></p></div>
        <div class="card"><i>02</i><h3><?= t('d2') ?></h3><p><?= t('d2p') ?></p></div>
        <div class="card"><i>03</i><h3><?= t('d3') ?></h3><p><?= t('d3p') ?></p></div>
        <div class="card"><i>04</i><h3><?= t('d4') ?></h3><p><?= t('d4p') ?></p><span class="vip"><?= t('v4') ?></span></div>
      </div>
    </div>
  </section>

  <div class="dash"></div>

  <!-- SVC BANNER -->
  <section class="svc">
    <div class="container">
      <h2><?= t('st') ?></h2>
      <p><?= t('sp') ?></p>
      <div class="btns">
        <a class="btn red" href="<?= url('hub.php') ?>"><?= t('sb') ?></a>
      </div>
    </div>
  </section>

  <!-- CLIENTS -->
  <section class="band light2">
    <div class="container sec" style="padding-bottom: 0">
      <h2 class="sec-title"><?= t('kt') ?></h2>
    </div>
    <div class="mq">
      <div class="mq-track" id="logos">
        <?php
          $partners = require __DIR__ . '/includes/data/partners.php';
          foreach (array_merge($partners, $partners) as $p):
        ?>
          <div><img loading="lazy" alt="" src="<?= url('img/public/logistic/partners/' . $p) ?>"></div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- REVIEWS -->
  <section class="band rv">
    <div class="container sec">
      <h2 class="sec-title"><?= t('rvt') ?></h2>
      <div class="rv-nav">
        <button type="button" aria-label="Назад" onclick="rvScroll(-1)">←</button>
        <button type="button" aria-label="Вперед" onclick="rvScroll(1)">→</button>
      </div>
      <div class="rv-wrap">
        <div class="rv-track" id="rv-track">
          <?php foreach (require __DIR__ . '/includes/data/reviews.php' as $r): ?>
            <div class="rv-card">
              <p><?= e($r['text'][LANG]) ?></p>
              <b><?= e($r['name'][LANG]) ?></b>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta">
    <div class="container">
      <h2><?= t('ct') ?></h2>
      <p><?= t('cp') ?></p>
      <div class="btns">
        <a class="btn red mono" href="tel:+380660339009">+380 66 033 90 09</a>
        <a class="btn" href="<?= url('contacts.php') ?>"><?= t('b3b') ?></a>
      </div>
    </div>
  </section>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <script>
    window.I18N = {
      on:  <?= json_encode(t('on'),  JSON_UNESCAPED_UNICODE) ?>,
      off: <?= json_encode(t('off'), JSON_UNESCAPED_UNICODE) ?>
    };
  </script>
  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>

  <script src="https://widgets.binotel.com/getcall/widgets/ryc57r4lgh89owjne5sv.js" async></script>
</body>
</html>