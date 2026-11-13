<?php
/**
 * Загрузчик products.json + хелперы.
 * Автоматически ищет products.json в нескольких местах.
 */

function _findProductsJsonPath(): ?string {
    static $found = false;

    if ($found !== false) return $found;

    $root = dirname(__DIR__, 2); // includes/data → корень проекта

    $candidates = [
        $root . '/products.json',
        $root . '/data/products.json',
        $root . '/json/products.json',
        $root . '/assets/products.json',
        $root . '/catalog/products.json',
        __DIR__ . '/products.json',
    ];

    foreach ($candidates as $path) {
        if (is_file($path)) return $found = $path;
    }
    return $found = null;
}

function loadProductsInventory(): array {
    static $cache = null;
    if ($cache !== null) return $cache;

    $path = _findProductsJsonPath();
    if ($path === null) return $cache = [];

    $raw = json_decode(file_get_contents($path), true);
    if (!is_array($raw) || empty($raw['inventory'])) return $cache = [];

    return $cache = $raw['inventory'];
}

function loadAllProducts(): array {
    static $cache = null;
    if ($cache !== null) return $cache;

    $groupMap = [
        'used_tachographs' => 'tachographs',
        'new_tachographs'  => 'tachographs',
        'sensors'          => 'sensors',
        'parts'            => 'parts',
        'radio'            => 'radio',
        'antennas'         => 'antennas',
    ];

    $products = [];
    foreach (loadProductsInventory() as $key => $items) {
        if (!is_array($items)) continue;
        $group = $groupMap[$key] ?? $key;

        foreach ($items as $item) {
            $id = $item['id'] ?? '';
            if ($id === '') continue;

            $hasVariations = !empty($item['has_variations']) && !empty($item['variations']);

            if ($hasVariations) {
                $variations = $item['variations'];
                $prices     = array_filter(
                    array_map(fn($v) => (int) ($v['price'] ?? 0), $variations),
                    fn($p) => $p > 0
                );
                $conditions = array_values(array_unique(
                    array_map(fn($v) => $v['condition'] ?? 'new', $variations)
                ));

                $products[$id] = [
                    'id'              => $id,
                    'name'            => $item['name']        ?? [],
                    'description'     => $item['description'] ?? [],
                    'image_url'       => $item['image_url']   ?? '',
                    'group'           => $group,
                    'has_variations'  => true,
                    'variations'      => $variations,
                    'conditions'      => $conditions,
                    'min_price'       => $prices ? min($prices) : 0,
                    'max_price'       => $prices ? max($prices) : 0,
                    'price'           => 0,
                    'condition'       => $item['condition'] ?? 'new',
                    'is_kit'          => strpos((string) $id, 'kit-') === 0,
                    'default_variation' => $variations[0] ?? null,
                ];
            } else {
                $products[$id] = [
                    'id'             => $id,
                    'name'           => $item['name']        ?? [],
                    'description'    => $item['description'] ?? [],
                    'image_url'      => $item['image_url']   ?? '',
                    'group'          => $group,
                    'has_variations' => false,
                    'variations'     => [],
                    'conditions'     => [$item['condition'] ?? 'new'],
                    'min_price'      => 0,
                    'max_price'      => 0,
                    'price'          => (int) ($item['price'] ?? 0),
                    'condition'      => $item['condition'] ?? 'new',
                    'is_kit'         => false,
                ];
            }
        }
    }

    return $cache = $products;
}

/**
 * Ищет товар по id. Если не нашли — пробует найти по variation.id
 * (например, старая ссылка «product.html?id=u1381-24»).
 * Возвращает [product, variation|null].
 */
function findProductWithVariation(string $id): ?array {
    $all = loadAllProducts();

    // 1) Точное совпадение по id товара
    if (isset($all[$id])) {
        return ['product' => $all[$id], 'variation' => null];
    }

    // 2) Поиск по variation.id
    foreach ($all as $product) {
        if (empty($product['variations'])) continue;
        foreach ($product['variations'] as $v) {
            if (($v['id'] ?? '') === $id) {
                return ['product' => $product, 'variation' => $v];
            }
        }
    }
    return null;
}

function findProductById(string $id): ?array {
    $found = findProductWithVariation($id);
    return $found ? $found['product'] : null;
}

function productUrl(array $p, ?array $variation = null): string {
    $params = ['id' => $p['id']];
    $v = $variation ?: ($p['default_variation'] ?? null);
    if ($v) {
        if (!empty($v['tacho_model'])) $params['tacho'] = $v['tacho_model'];
        if (!empty($v['voltage']))     $params['volt']  = $v['voltage'];
        if (!empty($v['condition']))   $params['cond']  = $v['condition'];
    }
    return url('product.php') . '?' . http_build_query($params);
}

function productPriceLabel(array $p): string {
    if ($p['has_variations']) {
        $min = $p['min_price'];
        $max = $p['max_price'];
        if ($min > 0 && $max > 0 && $min !== $max) return "$min - $max " . t('cat_currency');
        if ($min > 0) return "$min " . t('cat_currency');
    } elseif ($p['price'] > 0) {
        return $p['price'] . ' ' . t('cat_currency');
    }
    return t('cat_price_ask');
}

function findActiveVariation(array $product, array $query): ?array {
    if (empty($product['variations'])) return null;

    $tacho = $query['tacho'] ?? null;
    $volt  = $query['volt']  ?? null;
    $cond  = $query['cond']  ?? null;

    foreach ($product['variations'] as $v) {
        if ($tacho && ($v['tacho_model'] ?? '') !== $tacho) continue;
        if ($volt  && ($v['voltage']     ?? '') !== $volt)  continue;
        if ($cond  && ($v['condition']   ?? '') !== $cond)  continue;
        return $v;
    }
    return $product['default_variation'] ?? $product['variations'][0];
}