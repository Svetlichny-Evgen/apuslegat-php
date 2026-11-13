<?php
function url(string $path = ''): string {
    return BASE_URL . ltrim($path, '/');
}

function t(string $key, string $default = ''): string {
    global $TRANSLATIONS;
    return $TRANSLATIONS[$key] ?? $default;
}

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

/** Возвращает URL текущей страницы с указанным языком (?lang=...) */
function langUrl(string $lang): string {
    $uri   = $_SERVER['REQUEST_URI'] ?? '/';
    $parts = parse_url($uri);
    $path  = $parts['path'] ?? '/';
    $q     = [];
    if (!empty($parts['query'])) parse_str($parts['query'], $q);
    $q['lang'] = $lang;
    return $path . '?' . http_build_query($q);
}

/** «N вариантов» с правильным склонением по языку */
function pluralVariants(int $n, string $lang): string {
    if ($lang === 'uk') {
        $w = $n === 1 ? 'варіант' : (($n >= 2 && $n <= 4) ? 'варіанти' : 'варіантів');
    } else {
        $w = $n === 1 ? 'вариант' : (($n >= 2 && $n <= 4) ? 'варианта' : 'вариантов');
    }
    return "$n $w";
}