<?php
require __DIR__ . "/config/config.php";
require __DIR__ . "/includes/functions.php";

$TRANSLATIONS = require __DIR__ . "/includes/lang/" . LANG . ".php";

$PAGE       = "service-new";
$PAGE_TITLE = t("t13") . " — APUS LEGAT";
$PAGE_DESC  = t("d13");
$PAGE_CSS   = [];
$PAGE_JS    = ["main"];

require __DIR__ . "/includes/head.php";
?>
<body>
  <?php require __DIR__ . "/includes/header.php"; ?>

  <section class="svc" style="padding: 80px 0;">
    <div class="container">
      <a href="<?= url("hub.php") ?>" style="display:inline-block;color:#e60c0c;margin-bottom:20px;text-decoration:none;font-weight:800;">← НАЗАД ДО ПОСЛУГ</a>
      <h1 style="font-size: clamp(30px, 5vw, 50px); margin-bottom: 20px; font-weight: 900; text-transform: uppercase;"><?= t("t13") ?></h1>
      <p style="font-size: 16px; color: #ccc; max-width: 800px; line-height: 1.6; margin-bottom: 40px;"><?= t("d13") ?></p>
      
      <div style="background:#0a0a0a; padding:40px; border-left:4px solid #e60c0c; border-radius:4px; max-width:600px;">
        <h2 style="margin-bottom:15px; font-weight:900; text-transform:uppercase;">Зв'яжіться з нами</h2>
        <p style="margin-bottom:25px; color:#aaa;">Залиште заявку і ми підберемо для вас найкраще рішення.</p>
        <a href="tel:+380660339009" class="btn red" style="display:inline-block;padding:15px 30px;background:#e60c0c;color:#fff;text-decoration:none;font-weight:900;text-transform:uppercase;letter-spacing:1px;">+380 66 033 90 09</a>
      </div>
    </div>
  </section>

  <?php require __DIR__ . "/includes/footer.php"; ?>
  <?php foreach ($PAGE_JS as $js): ?>
    <script src="<?= url("assets/js/" . $js . ".js") ?>"></script>
  <?php endforeach; ?>
</body>
</html>