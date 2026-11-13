<?php
/**
 * Универсальный обработчик заявок → Telegram.
 * Формат сообщения собирается по факту наличия полей в $_POST.
 *
 * Базовые поля (всегда):
 *   name, phone, service, referer(из HTTP_REFERER)
 *
 * Блок корзины (если пришёл cart_items):
 *   delivery, address, cart_items (JSON), cart_total, cart_count
 *
 * Блок калькулятора тахографа (если пришёл calculator_brand или calculator_voltage):
 *   calculator_brand, calculator_voltage, calculator_price
 *
 * Если ни корзины, ни калькулятора — считаем заявкой на Smart-тахограф.
 */

date_default_timezone_set('Europe/Kyiv');

// Никаких warning/deprecated в тело ответа — только в лог.
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/error.log');
error_reporting(E_ALL);

// ==========================================
// КОНФИГ
// ==========================================
const BOT_TOKEN = '8731025279:AAFl1clFVLXY3ikXnG9_2ma7I8fnTohcO9o';
const CHAT_ID   = '-1003556856091';
const LOG_FILE  = __DIR__ . '/telegram_debug.log';

// Куда редиректить после успешной/неуспешной отправки
const URL_SUCCESS = '../thank-you.html?status=success';
const URL_ERROR   = '../index.html?status=error';
const URL_DEFAULT = '../index.html';

// ==========================================
// ХЕЛПЕРЫ
// ==========================================

function logMsg(string $text): void {
    @file_put_contents(LOG_FILE, date('Y-m-d H:i:s') . ' - ' . $text . "\n", FILE_APPEND);
}

/** Чистое строковое поле из POST (без тегов, обрезано). */
function p(string $key, string $default = ''): string {
    $v = $_POST[$key] ?? $default;
    if (is_array($v)) return $default;
    return trim(strip_tags((string)$v));
}

/** Экранирование под HTML-parse_mode Telegram. */
function e(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

/** Есть ли в POST хоть одно непустое из перечисленных полей. */
function hasAny(array $keys): bool {
    foreach ($keys as $k) {
        if (isset($_POST[$k]) && trim((string)$_POST[$k]) !== '') {
            return true;
        }
    }
    return false;
}

/** Отправка в Telegram. true при HTTP 200. */
function sendTelegram(string $message): bool {
    $url = 'https://api.telegram.org/bot' . BOT_TOKEN . '/sendMessage';
    $payload = [
        'chat_id'                  => CHAT_ID,
        'text'                     => $message,
        'parse_mode'               => 'HTML',
        'disable_web_page_preview' => true,
    ];

    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // curl_close() не вызываем: с PHP 8.0 ресурс освобождается сам,
        // а с 8.5 функция deprecated и пишет warning в вывод.
        logMsg("Telegram cURL: HTTP {$httpCode}");
        return $httpCode === 200;
    }

    $ctx = stream_context_create([
        'http' => [
            'method'        => 'POST',
            'header'        => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content'       => http_build_query($payload),
            'timeout'       => 30,
            'ignore_errors' => true,
        ],
        'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
    ]);
    $response = @file_get_contents($url, false, $ctx);
    logMsg('Telegram file_get_contents: ' . ($response !== false ? 'OK' : 'FAIL'));
    return $response !== false;
}

/** Парсит cart_items (JSON) → строку «1. Название — цена × qty». */
function buildCartItemsList(string $json): string {
    $items = json_decode($json, true);
    if (!is_array($items) || !$items) {
        return '—';
    }
    $out = '';
    $i = 1;
    foreach ($items as $item) {
        $name  = trim(strip_tags((string)($item['name'] ?? 'Товар')));
        $price = trim(strip_tags((string)($item['price_text'] ?? $item['price'] ?? '')));
        $qty   = (int)($item['quantity'] ?? 1);
        $line  = "   {$i}. {$name}";
        if ($price !== '') {
            $line .= " — {$price}";
        }
        if ($qty > 1) {
            $line .= " × {$qty}";
        }
        $out .= "\n" . $line;
        $i++;
    }
    return $out;
}

/** Текст доставки по коду. */
function deliveryText(string $code): string {
    return match ($code) {
        'np'     => 'Нова Пошта (Відділення)',
        'pickup' => 'Самовивіз (Харків)',
        default  => $code !== '' ? $code : '—',
    };
}

// ==========================================
// ОСНОВНАЯ ЛОГИКА
// ==========================================

header('X-Content-Type-Options: nosniff');

// Только POST
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: ' . URL_DEFAULT);
    exit;
}

logMsg('POST: ' . json_encode($_POST, JSON_UNESCAPED_UNICODE));

// ── Базовые поля ─────────────────────────────
$referer = $_SERVER['HTTP_REFERER'] ?? 'Невідомо';
$ip      = $_SERVER['REMOTE_ADDR']   ?? 'Невідомо';

$name    = p('name');
$phone   = p('phone');
$service = p('service');

// ── Определяем «ветку» ───────────────────────
$isCart   = hasAny(['cart_items']);
$isTacho  = hasAny(['calculator_brand', 'calculator_voltage', 'calculator_price']);
$isSmart  = !$isCart && !$isTacho;

// ── Валидация ────────────────────────────────
$errors = [];
if ($name === '')    $errors[] = 'name';
if (strlen(preg_replace('/\D/', '', $phone)) < 10) $errors[] = 'phone';

if ($isCart && p('address') === '') $errors[] = 'address';

if ($errors) {
    logMsg('❌ validation: ' . implode(',', $errors));

    $isAjaxErr = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
              && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    if ($isAjaxErr) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'error',
            'errors' => $errors,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    header('Location: ' . URL_ERROR);
    exit;
}

// ── Сборка сообщения ─────────────────────────

$SEP = "━━━━━━━━━━━━━━━━━━━━\n";

$msg  = "🔔 <b>НОВАЯ ЗАЯВКА</b>\n";
$msg .= $SEP;

// Услуга
if ($isCart) {
    $msg .= "🛒 <b>Услуга:</b> Замовлення в магазині\n";
} elseif ($isTacho) {
    $msg .= "⚙️ <b>Услуга:</b> Установка тахографа\n";
} else {
    $svc = $service !== '' ? $service : 'Установка Smart тахографа';
    $msg .= "⚙️ <b>Услуга:</b> " . e($svc) . "\n";
}

// ФИО и телефон
$msg .= "👤 <b>ФИО:</b> " . e($name) . "\n";
$msg .= "📞 <b>Телефон:</b> <a href=\"tel:" . e($phone) . "\">" . e($phone) . "</a>\n";

$msg .= $SEP;

// ── Блок корзины ─────────────────────────────
if ($isCart) {
    $delivery  = deliveryText(p('delivery'));
    $address   = p('address');
    $cartItems = buildCartItemsList(p('cart_items'));
    $cartTotal = p('cart_total');
    $cartCount = p('cart_count');

    $msg .= "📦 <b>Доставка:</b> " . e($delivery) . "\n";
    $msg .= "📍 <b>Адрес:</b> " . e($address) . "\n";
    $msg .= $SEP;
    $msg .= "🛍️ <b>Товары в заказе" . ($cartCount !== '' ? " ({$cartCount} шт.)" : "") . ":</b>" . $cartItems . "\n";
    $msg .= $SEP;
    $msg .= "💰 <b>Итого к оплате:</b> " . e($cartTotal !== '' ? $cartTotal : '—') . "\n";
    $msg .= $SEP;
}

// ── Блок калькулятора тахографа ──────────────
if ($isTacho) {
    $brand   = p('calculator_brand',   '—');
    $voltage = p('calculator_voltage', '—');
    $price   = p('calculator_price',   '—');

    $msg .= "🚛 <b>Марка авто:</b> " . e($brand) . "\n";
    $msg .= "⚡ <b>Бортовая сеть:</b> " . e($voltage) . "\n";
    $msg .= "💵 <b>Ориентировочная стоимость:</b> " . e($price) . "\n";
    $msg .= $SEP;
}

// ── Подвал ───────────────────────────────────
$msg .= "🔗 <b>Страница:</b>\n" . e($referer) . "\n";
$msg .= "🌐 <b>IP:</b> " . e($ip) . "\n";
$msg .= "🕐 <b>Время:</b> " . date('d.m.Y H:i:s');

// ── Отправка ─────────────────────────────────
$success = sendTelegram($msg);
if (!$success) {
    logMsg('❌ Telegram send failed');
}

// ── Ответ ────────────────────────────────────
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
       && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($isAjax) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'status'  => $success ? 'success' : 'error',
        'message' => $success ? 'OK' : 'Telegram send failed',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

header('Location: ' . ($success ? URL_SUCCESS : URL_ERROR));
exit;