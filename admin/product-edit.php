<?php
require __DIR__ . '/auth.php';
adminRequireLogin();
require __DIR__ . '/helpers.php';
require __DIR__ . '/layout.php';

$inventory = loadJson(productsPath());
if (empty($inventory['inventory'])) $inventory['inventory'] = [];
$inv = &$inventory['inventory'];

$categories = array_keys($inv);
if (!$categories) {
    $categories = ['tachographs','installation_kits','sensors','parts','radio','antennas'];
}

$id = $_GET['id'] ?? '';
$p  = null;
$origId = '';

if ($id !== '') {
    $ref = findProductRef($inv, $id);
    if ($ref) {
        $p = $ref['item'];
        $origId = $id;
    }
}

$isNew = ($p === null);
if ($isNew) {
    $p = [
        'id'             => '',
        'name'           => ['uk' => '', 'ru' => ''],
        'description'    => ['uk' => '', 'ru' => ''],
        'image_url'      => '',
        'has_variations' => false,
        'condition'      => 'new',
        'price'          => 0,
        'variations'     => [],
    ];
}

/* ===== Обработка POST ===== */
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();

    $newId    = trim($_POST['id'] ?? '');
    $category = $_POST['category'] ?? ($categories[0] ?? 'tachographs');
    $newOrig  = $_POST['orig_id'] ?? '';

    if ($newId === '') {
        $error = 'ID обов\'язковий';
    } elseif ($newOrig === '' && findProductRef($inv, $newId)) {
        $error = 'Товар з таким ID вже існує';
    } else {
        /* проверяем, что смена ID не конфликтует */
        if ($newOrig !== '' && $newOrig !== $newId && findProductRef($inv, $newId)) {
            $error = 'Новий ID вже зайнятий';
        }
    }

    if (!$error) {
        $product = [
            'id'             => $newId,
            'name'           => [
                'uk' => trim($_POST['name_uk'] ?? ''),
                'ru' => trim($_POST['name_ru'] ?? ''),
            ],
            'description'    => [
                'uk' => trim($_POST['desc_uk'] ?? ''),
                'ru' => trim($_POST['desc_ru'] ?? ''),
            ],
            'image_url'      => trim($_POST['image_url'] ?? ''),
            'has_variations' => !empty($_POST['has_variations']),
        ];

        if (!$product['has_variations']) {
            $product['condition'] = $_POST['condition'] ?? 'new';
            $product['price']     = (int) ($_POST['price'] ?? 0);
        } else {
            $vars = [];
            $vIds = $_POST['v_id'] ?? [];
            foreach ($vIds as $k => $vid) {
                $vid = trim($vid);
                if ($vid === '') continue;
                $v = [
                    'id'        => $vid,
                    'condition' => $_POST['v_condition'][$k] ?? 'new',
                    'price'     => (int) ($_POST['v_price'][$k] ?? 0),
                ];
                $sku = trim($_POST['v_sku'][$k] ?? '');
                if ($sku !== '') $v['sku'] = $sku;
                $tacho = trim($_POST['v_tacho'][$k] ?? '');
                if ($tacho !== '') $v['tacho_model'] = $tacho;
                $volt = trim($_POST['v_voltage'][$k] ?? '');
                if ($volt !== '') $v['voltage'] = $volt;
                $vars[] = $v;
            }
            $product['variations'] = $vars;
        }

        /* сохранение */
        backupFile(productsPath());

        if ($newOrig !== '') {
            $ref = findProductRef($inv, $newOrig);
            if ($ref) {
                if ($ref['cat'] !== $category) {
                    array_splice($inv[$ref['cat']], $ref['idx'], 1);
                    if (!isset($inv[$category])) $inv[$category] = [];
                    $inv[$category][] = $product;
                } else {
                    $inv[$ref['cat']][$ref['idx']] = $product;
                }
            }
        } else {
            if (!isset($inv[$category])) $inv[$category] = [];
            $inv[$category][] = $product;
        }

        if (saveJson(productsPath(), $inventory)) {
            flash('Товар збережено');
            redirect('products.php');
        }
        $error = 'Помилка збереження products.json';
    }

    /* сохраняем введённое в $p для отображения */
    $p = $product ?? $p;
    $p['id'] = $newId;
    $isNew = ($newOrig === '');
    $origId = $newOrig;
}

$title = $isNew ? 'Новий товар' : ('Редагування: ' . ($p['name']['uk'] ?: $p['id']));
adminHeader($title, 'products.php');
?>
<?php if ($error): ?>
  <div class="alert alert-err"><?= e($error) ?></div>
<?php endif; ?>

<form method="POST">
  <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">
  <input type="hidden" name="orig_id" value="<?= e($origId) ?>">

  <div class="form-grid">
    <div class="form-row">
      <label>ID * (латиниця, цифри, дефіс)</label>
      <input type="text" name="id" required value="<?= e($p['id']) ?>"
             pattern="[A-Za-z0-9\-_]+">
    </div>
    <div class="form-row">
      <label>Категорія</label>
      <select name="category">
        <?php
        $currentCat = $categories[0] ?? 'tachographs';
        if ($origId !== '') {
            $ref = findProductRef($inv, $origId);
            if ($ref) $currentCat = $ref['cat'];
        }
        foreach ($categories as $c):
        ?>
          <option value="<?= e($c) ?>" <?= $c === $currentCat ? 'selected' : '' ?>>
            <?= e($c) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-row">
      <label>Назва (UA)</label>
      <input type="text" name="name_uk" value="<?= e($p['name']['uk'] ?? '') ?>">
    </div>
    <div class="form-row">
      <label>Назва (RU)</label>
      <input type="text" name="name_ru" value="<?= e($p['name']['ru'] ?? '') ?>">
    </div>

    <div class="form-row full">
      <label>Опис (UA)</label>
      <textarea name="desc_uk"><?= e($p['description']['uk'] ?? '') ?></textarea>
    </div>
    <div class="form-row full">
      <label>Опис (RU)</label>
      <textarea name="desc_ru"><?= e($p['description']['ru'] ?? '') ?></textarea>
    </div>

    <div class="form-row full">
      <label>URL зображення (наприклад img/public/shop/xxx.webp)</label>
      <input type="text" name="image_url" value="<?= e($p['image_url'] ?? '') ?>">
    </div>
  </div>

  <div class="form-row checkbox">
    <label>
      <input type="checkbox" name="has_variations" id="has-vars"
             <?= !empty($p['has_variations']) ? 'checked' : '' ?>>
      Товар має варіації (напруги / стани / моделі тахографа)
    </label>
  </div>

  <div id="simple-block" style="<?= !empty($p['has_variations']) ? 'display:none' : '' ?>">
    <div class="form-grid">
      <div class="form-row">
        <label>Стан</label>
        <select name="condition">
          <option value="new"  <?= ($p['condition'] ?? '') === 'new'  ? 'selected' : '' ?>>Новий</option>
          <option value="used" <?= ($p['condition'] ?? '') === 'used' ? 'selected' : '' ?>>Б/В</option>
        </select>
      </div>
      <div class="form-row">
        <label>Ціна (грн)</label>
        <input type="number" name="price" min="0" value="<?= (int)($p['price'] ?? 0) ?>">
      </div>
    </div>
  </div>

  <div id="vars-block" class="variations-block"
       style="<?= !empty($p['has_variations']) ? '' : 'display:none' ?>">
    <h3>Варіації</h3>
    <div class="variation-row" style="font-size:10px;color:#666;text-transform:uppercase;letter-spacing:1px">
      <div>ID</div><div>Стан</div><div>Тахограф</div><div>Напруга</div><div>Ціна</div><div>SKU</div><div></div>
    </div>
    <div id="vars-list">
      <?php foreach (($p['variations'] ?? []) as $v): ?>
        <div class="variation-row">
          <input type="text" name="v_id[]" value="<?= e($v['id'] ?? '') ?>" placeholder="ID">
          <select name="v_condition[]">
            <option value="new"  <?= ($v['condition'] ?? '') === 'new'  ? 'selected' : '' ?>>Новий</option>
            <option value="used" <?= ($v['condition'] ?? '') === 'used' ? 'selected' : '' ?>>Б/В</option>
          </select>
          <input type="text" name="v_tacho[]"   value="<?= e($v['tacho_model'] ?? '') ?>" placeholder="1381">
          <input type="text" name="v_voltage[]" value="<?= e($v['voltage'] ?? '') ?>"     placeholder="24V">
          <input type="number" name="v_price[]" value="<?= (int)($v['price'] ?? 0) ?>"    min="0">
          <input type="text" name="v_sku[]"     value="<?= e($v['sku'] ?? '') ?>"         placeholder="SKU">
          <button type="button" class="del" onclick="this.parentElement.remove()">×</button>
        </div>
      <?php endforeach; ?>
    </div>
    <button type="button" class="btn btn-ghost btn-sm"
            onclick="addVariation()" style="margin-top:12px">+ Варіація</button>
  </div>

  <div class="btn-bar">
    <button type="submit" class="btn btn-primary">Зберегти</button>
    <a href="products.php" class="btn btn-ghost">← До списку</a>
    <?php if (!$isNew): ?>
      <a href="product-delete.php?id=<?= urlencode($origId) ?>"
         class="btn btn-danger"
         onclick="this.href='product-delete.php'; 
                  var f=document.createElement('form');
                  f.method='POST'; f.action='product-delete.php';
                  f.innerHTML='<input type=hidden name=_csrf value=\'<?= e(csrfToken()) ?>\'><input type=hidden name=id value=\'<?= e($origId) ?>\'>';
                  document.body.appendChild(f); f.submit();
                  return false;">Видалити</a>
    <?php endif; ?>
  </div>
</form>

<script>
document.getElementById('has-vars').addEventListener('change', function () {
    document.getElementById('simple-block').style.display = this.checked ? 'none' : '';
    document.getElementById('vars-block').style.display   = this.checked ? '' : 'none';
});

function addVariation() {
    var row = document.createElement('div');
    row.className = 'variation-row';
    row.innerHTML = ''
      + '<input type="text" name="v_id[]" placeholder="ID">'
      + '<select name="v_condition[]"><option value="new">Новий</option><option value="used">Б/В</option></select>'
      + '<input type="text" name="v_tacho[]" placeholder="1381">'
      + '<input type="text" name="v_voltage[]" placeholder="24V">'
      + '<input type="number" name="v_price[]" value="0" min="0">'
      + '<input type="text" name="v_sku[]" placeholder="SKU">'
      + '<button type="button" class="del" onclick="this.parentElement.remove()">×</button>';
    document.getElementById('vars-list').appendChild(row);
}
</script>

<?php adminFooter(); ?>