<?php
require __DIR__ . '/auth.php';
adminRequireLogin();
require __DIR__ . '/helpers.php';
require __DIR__ . '/layout.php';

$servicesPath = rootPath('includes/data/services.php');
$ukPath       = rootPath('includes/lang/uk.php');
$ruPath       = rootPath('includes/lang/ru.php');

$services = loadPhpArray($servicesPath);
$uk       = loadPhpArray($ukPath);
$ru       = loadPhpArray($ruPath);

$nFilters = ['n1','n2','n3','n4','n5','n6','n7','n8'];

$idx   = isset($_GET['idx']) ? (int) $_GET['idx'] : null;
$isNew = ($idx === null);

$data = [
    'file'  => 'service-new',
    'num'   => sprintf('%02d', count($services) + 1),
    'n'     => 'n1',
    't_key' => '',
    'd_key' => '',
    'p_key' => '',
    't_uk'  => '',
    'd_uk'  => '',
    't_ru'  => '',
    'd_ru'  => '',
    'p_uk'  => '',
    'p_ru'  => '',
];

if (!$isNew && isset($services[$idx])) {
    $s = $services[$idx];
    $data = [
        'file'  => $s['file'] ?? '',
        'num'   => $s['num']  ?? '',
        'n'     => $s['n']    ?? '',
        't_key' => $s['t']    ?? '',
        'd_key' => $s['d']    ?? '',
        'p_key' => $s['p']    ?? '',
        't_uk'  => $uk[$s['t'] ?? ''] ?? '',
        'd_uk'  => $uk[$s['d'] ?? ''] ?? '',
        't_ru'  => $ru[$s['t'] ?? ''] ?? '',
        'd_ru'  => $ru[$s['d'] ?? ''] ?? '',
        'p_uk'  => $uk[$s['p'] ?? ''] ?? '',
        'p_ru'  => $ru[$s['p'] ?? ''] ?? '',
    ];
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();

    $file   = trim($_POST['file'] ?? '');
    $num    = trim($_POST['num'] ?? '');
    $nCats  = $_POST['n_cats'] ?? [];
    $nStr   = implode(' ', array_values(array_intersect($nCats, $nFilters)));

    $t_uk = trim($_POST['t_uk'] ?? '');
    $d_uk = trim($_POST['d_uk'] ?? '');
    $t_ru = trim($_POST['t_ru'] ?? '');
    $d_ru = trim($_POST['d_ru'] ?? '');
    $p_uk = trim($_POST['p_uk'] ?? '');
    $p_ru = trim($_POST['p_ru'] ?? '');

    if ($file === '' || $num === '' || $t_uk === '' || $d_uk === '') {
        $error = 'Заповніть обов\'язкові поля (файл, номер, назва, опис UA)';
    } else {
        /* Ключи переводов */
        if ($isNew) {
            /* находим свободный номер для t/d ключей */
            $maxN = 0;
            foreach ($services as $s) {
                foreach (['t','d','p'] as $p) {
                    if (!empty($s[$p]) && preg_match('/^' . $p . '(\d+)$/', $s[$p], $m)) {
                        $maxN = max($maxN, (int)$m[1]);
                    }
                }
            }
            $tKey = 't' . ($maxN + 1);
            $dKey = 'd' . ($maxN + 1);
            $pKey = ($p_uk !== '' || $p_ru !== '') ? 'p' . ($maxN + 1) : '';
        } else {
            $tKey = $data['t_key'];
            $dKey = $data['d_key'];
            $pKey = ($p_uk !== '' || $p_ru !== '') ? ($data['p_key'] ?: 'p' . ($idx + 1)) : '';
        }

        $entry = [
            'file' => $file,
            'n'    => $nStr ?: 'n1',
            'num'  => $num,
            't'    => $tKey,
            'd'    => $dKey,
        ];
        if ($pKey !== '') $entry['p'] = $pKey;

        /* Обновляем services.php */
        if (!$isNew) {
            $services[$idx] = $entry;
        } else {
            $services[] = $entry;
        }

        /* Обновляем lang-файлы */
        $uk[$tKey] = $t_uk;
        $uk[$dKey] = $d_uk;
        $ru[$tKey] = $t_ru ?: $t_uk;
        $ru[$dKey] = $d_ru ?: $d_uk;

        if ($pKey !== '') {
            $uk[$pKey] = $p_uk;
            $ru[$pKey] = $p_ru ?: $p_uk;
        }

        backupFile($servicesPath);
        backupFile($ukPath);
        backupFile($ruPath);

        $ok = savePhpArray($servicesPath, $services)
           && savePhpArray($ukPath, $uk)
           && savePhpArray($ruPath, $ru);

        if ($ok) {
            flash('Послугу збережено');
            redirect('services.php');
        }
        $error = 'Помилка збереження файлів';
    }

    /* сохраняем в $data для отображения */
    $data = [
        'file' => $file, 'num' => $num, 'n' => $nStr,
        't_key' => $data['t_key'], 'd_key' => $data['d_key'], 'p_key' => $data['p_key'],
        't_uk' => $t_uk, 'd_uk' => $d_uk, 't_ru' => $t_ru, 'd_ru' => $d_ru,
        'p_uk' => $p_uk, 'p_ru' => $p_ru,
    ];
}

$title = $isNew ? 'Нова послуга' : 'Редагування послуги';
adminHeader($title, 'services.php');
?>
<?php if ($error): ?>
  <div class="alert alert-err"><?= e($error) ?></div>
<?php endif; ?>

<form method="POST">
  <input type="hidden" name="_csrf" value="<?= e(csrfToken()) ?>">

  <div class="form-grid">
    <div class="form-row">
      <label>Файл (без .php) *</label>
      <input type="text" name="file" required value="<?= e($data['file']) ?>"
             pattern="[A-Za-z0-9\-_]+"
             placeholder="service-new">
      <div class="muted" style="margin-top:5px">
        Має відповідати файлу <code>service-new.php</code>
      </div>
    </div>
    <div class="form-row">
      <label>Номер (01–99) *</label>
      <input type="text" name="num" required maxlength="2"
             value="<?= e($data['num']) ?>">
    </div>

    <div class="form-row full">
      <label>Фільтри (до яких чипів в hub)</label>
      <div style="display:flex;flex-wrap:wrap;gap:16px;padding:8px 0">
        <?php foreach ($nFilters as $nf):
            $checked = in_array($nf, explode(' ', $data['n']), true);
            $lbl = $uk[$nf] ?? $nf;
        ?>
          <label style="display:flex;align-items:center;gap:6px;color:#ccc;font-size:12px;cursor:pointer">
            <input type="checkbox" name="n_cats[]" value="<?= e($nf) ?>" <?= $checked ? 'checked' : '' ?>>
            <?= e($lbl) ?>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="form-row">
      <label>Назва (UA) *</label>
      <input type="text" name="t_uk" required value="<?= e($data['t_uk']) ?>">
    </div>
    <div class="form-row">
      <label>Назва (RU)</label>
      <input type="text" name="t_ru" value="<?= e($data['t_ru']) ?>"
             placeholder="Якщо порожньо — буде як UA">
    </div>

    <div class="form-row full">
      <label>Опис (UA) *</label>
      <textarea name="d_uk" required><?= e($data['d_uk']) ?></textarea>
    </div>
    <div class="form-row full">
      <label>Опис (RU)</label>
      <textarea name="d_ru" placeholder="Якщо порожньо — буде як UA"><?= e($data['d_ru']) ?></textarea>
    </div>

    <div class="form-row">
      <label>Ціна (UA) — опційно</label>
      <input type="text" name="p_uk" value="<?= e($data['p_uk']) ?>"
             placeholder="від 4 500 грн">
    </div>
    <div class="form-row">
      <label>Ціна (RU) — опційно</label>
      <input type="text" name="p_ru" value="<?= e($data['p_ru']) ?>"
             placeholder="от 4 500 грн">
    </div>
  </div>

  <div class="btn-bar">
    <button type="submit" class="btn btn-primary">Зберегти</button>
    <a href="services.php" class="btn btn-ghost">← До списку</a>
  </div>
</form>

<?php adminFooter(); ?>