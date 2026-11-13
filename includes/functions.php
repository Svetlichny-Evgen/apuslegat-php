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