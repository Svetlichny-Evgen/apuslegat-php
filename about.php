<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'about';
$PAGE_TITLE = t('about_page_title');
$PAGE_DESC  = t('about_page_desc');
$PAGE_CSS   = ['about'];
$PAGE_JS    = ['main', 'about'];

$team = require __DIR__ . '/includes/data/team.php';

require __DIR__ . '/includes/head.php';
?>
<body>
  <?php require __DIR__ . '/includes/header.php'; ?>

  <main class="section-padding">
    <div class="hero-text-block">
      <h1><?= t('about_title') ?></h1>
      <div class="description-grid">
        <p><?= t('about_desc_1') ?></p>
        <p><?= t('about_desc_2') ?></p>
      </div>
    </div>

    <div class="fleet-section">
      <div class="fleet-card tr-1">
        <h3>RENAULT T520</h3>
        <p><?= t('fleet_t520_role') ?></p>
      </div>
      <div class="fleet-card tr-2">
        <h3>RENAULT T480</h3>
        <p><?= t('fleet_t480_role') ?></p>
      </div>
      <div class="fleet-card tr-3">
        <h3>MERCEDES SPRINTER</h3>
        <p><?= t('fleet_sprinter_role') ?></p>
      </div>
    </div>

    <h2 class="team-title"><?= t('about_team_title') ?></h2>
    <div class="ticker-container" id="slider-container">
      <div class="ticker-track" id="slider-track">
        <?php foreach ($team as $m): ?>
          <div class="team-member-card">
            <div class="photo-box">
              <img src="<?= url('img/public/personal/' . $m['strict']) ?>"
                   class="strict-photo" alt="<?= e($m['name'][LANG]) ?>">
              <img src="<?= url('img/public/personal/' . $m['fun']) ?>"
                   class="fun-photo"    alt="<?= e($m['name'][LANG]) ?>">
            </div>
            <div class="member-info">
              <h4><?= e($m['name'][LANG]) ?></h4>
              <span class="role-txt"><?= t($m['role_key']) ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>

  <!-- Плавающая кнопка звонка (моб.) — в исходнике была закомментирована багом merge-конфликта -->
  <a href="tel:+380630339009" class="fixed-call-btn" aria-label="Зателефонувати">
    <svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
  </a>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>

  <script src="https://widgets.binotel.com/getcall/widgets/qeo3g4bi10qxm3u9yz8a.js" async></script>
</body>
</html>