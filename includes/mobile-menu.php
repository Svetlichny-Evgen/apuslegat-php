<div class="mobile-menu" id="mm">
  <a href="<?= url('index.php') ?>"    onclick="toggleMenu()"><?= t('home') ?></a>
  <a href="<?= url('about.php') ?>"    onclick="toggleMenu()"><?= t('about') ?></a>
  <a href="<?= url('catalog.php') ?>"  onclick="toggleMenu()"><?= t('shop') ?></a>
  <a href="<?= url('contacts.php') ?>" onclick="toggleMenu()"><?= t('contacts') ?></a>
  <div class="mobile-lang-switcher">
    <a href="?lang=uk" class="mobile-lang-btn <?= LANG === 'uk' ? 'active' : '' ?>" onclick="toggleMenu()">UA</a>
    <a href="?lang=ru" class="mobile-lang-btn <?= LANG === 'ru' ? 'active' : '' ?>" onclick="toggleMenu()">RU</a>
  </div>
</div>