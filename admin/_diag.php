<?php
header('Content-Type: text/plain; charset=utf-8');

echo "=== SERVER ===\n";
echo "SCRIPT_NAME  : " . ($_SERVER['SCRIPT_NAME'] ?? '') . "\n";
echo "PHP version  : " . PHP_VERSION . "\n";
echo "session.save : " . ini_get('session.save_path') . "\n\n";

echo "=== SESSIONS ===\n";
session_start();
echo "session_id   : " . session_id() . "\n";
$_SESSION['test'] = 'ok';
echo "written test : ok\n";
echo "read test    : " . ($_SESSION['test'] ?? 'MISSING') . "\n\n";

echo "=== CONFIG FILE ===\n";
$path = __DIR__ . '/config.json';
echo "path         : $path\n";
echo "exists       : " . (is_file($path) ? 'YES' : 'NO') . "\n";
echo "size         : " . (is_file($path) ? filesize($path) : 0) . " bytes\n";
echo "is_writable  : " . (is_writable($path) ? 'YES' : 'NO') . "\n";
echo "raw content  : " . (is_file($path) ? file_get_contents($path) : '—') . "\n\n";

echo "=== AUTH CHECK ===\n";
$cfg = json_decode(is_file($path) ? file_get_contents($path) : '', true);
echo "json_decode  : " . (is_array($cfg) ? 'OK' : 'FAILED') . "\n";
$hash = $cfg['password_hash'] ?? '';
echo "hash length  : " . strlen($hash) . "\n";
echo "hash value   : $hash\n";
if ($hash) {
    echo "verify admin123 : " . (password_verify('admin123', $hash) ? 'YES ✓' : 'NO ✗') . "\n";
}

echo "\n=== WRITE TEST ===\n";
$test = __DIR__ . '/_write_test.json';
$ok = @file_put_contents($test, '{"x":1}', LOCK_EX) !== false;
echo "can write    : " . ($ok ? 'YES ✓' : 'NO ✗') . "\n";
if ($ok) {
    echo "read back    : " . file_get_contents($test) . "\n";
    @unlink($test);
}