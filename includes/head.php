<?php
/** Ожидает переменные: $PAGE_TITLE, $PAGE_DESC, $PAGE_CSS, $PAGE_JS */
$PAGE_CSS = $PAGE_CSS ?? [];
$PAGE_JS  = $PAGE_JS  ?? [];
?>
<!doctype html>
<html lang="<?= LANG ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <base href="<?= BASE_URL ?>">
  <link rel="icon" type="image/png" href="img/public/favicon.png">
  <title><?= e($PAGE_TITLE ?? 'APUS LEGAT') ?></title>
  <meta name="description" content="<?= e($PAGE_DESC ?? '') ?>">

  <script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
    var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
    j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
    f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-N89VVZFR');
  </script>

  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= url('assets/css/base.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/header.css') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/footer.css') ?>">

  <?php foreach ($PAGE_CSS as $css): ?>
    <link rel="stylesheet" href="<?= url('assets/css/' . basename($css) . '.css') ?>">
  <?php endforeach; ?>

  <?php foreach (($PAGE_EXTERNAL_CSS ?? []) as $extCss): ?>
    <link rel="stylesheet" href="<?= e($extCss) ?>">
  <?php endforeach; ?>
</head>

  <?php foreach (($HEAD_JS ?? []) as $js): ?>
    <script src="<?= url('assets/js/' . $js . '.js') ?>"></script>
  <?php endforeach; ?>
</head>