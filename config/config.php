<?php
// ==== Язык ========================================================
if (isset($_GET['lang']) && in_array($_GET['lang'], ['uk', 'ru'], true)) {
    setcookie('lang', $_GET['lang'], time() + 86400 * 365, '/', '', false, true);
    $_COOKIE['lang'] = $_GET['lang'];
}
$__lang = $_COOKIE['lang'] ?? 'uk';
if (!in_array($__lang, ['uk', 'ru'], true)) $__lang = 'uk';
define('LANG', $__lang);

// ==== Base URL (абсолютный, со схемой и хостом) ====================
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'] ?? 'localhost';

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$subdir     = rtrim(dirname($scriptName), '/\\');   // '' для корня, '/apuslegat' для подпапки
if ($subdir === '.' ) $subdir = '';                 // защита от SCRIPT_NAME без слэша

define('BASE_URL', $scheme . '://' . $host . $subdir . '/');