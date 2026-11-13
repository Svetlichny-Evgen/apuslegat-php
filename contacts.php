<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'contacts';
$PAGE_TITLE = t('contacts_page_title');
$PAGE_DESC  = t('contacts_page_desc');
$PAGE_CSS   = ['contacts'];
$PAGE_JS    = ['main', 'contacts'];

$departments = require __DIR__ . '/includes/data/contacts.php';

require __DIR__ . '/includes/head.php';
?>
<body>
  <div class="bg-glow"></div>

  <?php require __DIR__ . '/includes/header.php'; ?>

  <main class="contact-section">
    <div class="contact-grid">
      <div class="info-side">
        <div class="title-block">
          <h1><?= t('contacts_title') ?></h1>
        </div>

        <?php foreach ($departments as $d): ?>
          <div class="dept-card">
            <h3><?= t($d['key']) ?></h3>
            <?php foreach ($d['phones'] as $p): ?>
              <a href="tel:<?= e($p['tel']) ?>"
                 class="phone-link"
                 data-tg="<?= e($p['tg']) ?>"><?= e($p['label']) ?></a>
            <?php endforeach; ?>
            <a href="mailto:<?= e($d['email']) ?>"
               class="mail-link"><?= e($d['email']) ?></a>
          </div>
        <?php endforeach; ?>

        <div class="info-bar">
          <div class="info-item">
            <h4><?= t('contacts_label_addr') ?></h4>
            <p><?= t('contacts_val_addr') ?></p>
          </div>
          <div class="info-item">
            <h4><?= t('contacts_label_sched') ?></h4>
            <p><?= t('contacts_val_sched') ?></p>
          </div>
        </div>
      </div>

      <div class="map-side">
        <div class="map-box">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2567.925565578768!2d36.270802!3d49.911498!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDnCsDU0JzQxLjQiTiAzNsKwMTYnMTQuOSJF!5e0!3m2!1s<?= LANG ?>!2sua!4v1710000000000!5m2!1s<?= LANG ?>!2sua"
            loading="lazy"
            allowfullscreen
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>
      </div>
    </div>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>

  <script src="https://widgets.binotel.com/getcall/widgets/qeo3g4bi10qxm3u9yz8a.js" async></script>
</body>
</html>