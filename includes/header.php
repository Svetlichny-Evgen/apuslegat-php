<?php require __DIR__ . '/svg-symbols.php'; ?>
<header class="main-header">
  <div class="nav-flex">
    <a href="<?= url('index.php') ?>" class="logo">APUS<span>LEGAT</span></a>
    <div class="nav-right">
      <nav class="nav-links">
        <a href="<?= url('index.php') ?>"    class="<?= ($PAGE ?? '') === 'home' ? 'on' : '' ?>"><?= t('home') ?></a>
        <a href="<?= url('about.php') ?>"    class="<?= ($PAGE ?? '') === 'about' ? 'on' : '' ?>"><?= t('about') ?></a>
        <a href="<?= url('catalog.php') ?>"  class="<?= ($PAGE ?? '') === 'catalog' ? 'on' : '' ?>"><?= t('shop') ?></a>
        <a href="<?= url('contacts.php') ?>" class="<?= ($PAGE ?? '') === 'contacts' ? 'on' : '' ?>"><?= t('contacts') ?></a>
      </nav>
      <div class="lang-switcher">
        <a href="?lang=uk" class="lang-btn <?= LANG === 'uk' ? 'active' : '' ?>">UA</a>
        <a href="?lang=ru" class="lang-btn <?= LANG === 'ru' ? 'active' : '' ?>">RU</a>
      </div>
      <div class="burger" onclick="toggleMenu()">
        <span></span><span></span><span></span>
      </div>
    </div>
  </div>
</header>
<?php require __DIR__ . '/mobile-menu.php'; ?>