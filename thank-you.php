<?php
require __DIR__ . '/config/config.php';
require __DIR__ . '/includes/functions.php';

$TRANSLATIONS = require __DIR__ . '/includes/lang/' . LANG . '.php';

$PAGE       = 'thank-you';
$PAGE_TITLE = t('ty_page_title');
$PAGE_DESC  = '';
$PAGE_CSS   = ['thank-you'];
$PAGE_JS    = ['main'];

require __DIR__ . '/includes/head.php';
?>
<body>
  <div class="bg-glow"></div>

  <?php require __DIR__ . '/includes/header.php'; ?>

  <main class="thank-you-section">
    <div class="thank-you-card">
      <div class="icon-circle">
        <svg class="check-mark" viewBox="0 0 24 24">
          <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
      </div>
      <h1><?= t('ty_title') ?></h1>
      <p><?= t('ty_desc') ?></p>
      <a href="<?= url('index.php') ?>" class="btn-back"><?= t('ty_btn_back') ?></a>
    </div>
  </main>

  <?php require __DIR__ . '/includes/footer.php'; ?>

  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>
</body>
</html>