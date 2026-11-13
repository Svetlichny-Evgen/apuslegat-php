<?php
/**
 * Список послуг для hub.php.
 * file — имя php-файла без .php
 * n    — категории через пробел (для чипов-фильтров)
 * num  — порядковый номер "01"…"12"
 * t    — ключ заголовка в translations
 * d    — ключ описания в translations
 * p    — (опц.) ключ цены в translations
 */
return [
    ['file' => 'service-otk',           'n' => 'n1',    'num' => '01', 't' => 't1',  'd' => 'd1'],
    ['file' => 'service-tacho',         'n' => 'n2',    'num' => '02', 't' => 't2',  'd' => 'd2'],
    ['file' => 'tacho-install',         'n' => 'n2',    'num' => '03', 't' => 't3',  'd' => 'd3'],
    ['file' => 'walkie-talkie-install', 'n' => 'n7',    'num' => '04', 't' => 't4',  'd' => 'd4'],
    ['file' => 'service-heaters',       'n' => 'n7',    'num' => '05', 't' => 't5',  'd' => 'd5'],
    ['file' => 'service-cert-auto',     'n' => 'n3',    'num' => '06', 't' => 't6',  'd' => 'd6',  'p' => 'p6'],
    ['file' => 'service-cert-tanks',    'n' => 'n4',    'num' => '07', 't' => 't7',  'd' => 'd7'],
    ['file' => 'service-extra',         'n' => 'n3',    'num' => '08', 't' => 't8',  'd' => 'd8'],
    ['file' => 'documents',             'n' => 'n3 n8', 'num' => '09', 't' => 't9',  'd' => 'd9',  'p' => 'p9'],
    ['file' => 'logistics',             'n' => 'n4 n5', 'num' => '10', 't' => 't10', 'd' => 'd10'],
    ['file' => 'parking',               'n' => 'n6',    'num' => '11', 't' => 't11', 'd' => 'd11', 'p' => 'p11'],
    ['file' => 'catalog',               'n' => 'n2 n7', 'num' => '12', 't' => 't12', 'd' => 'd12'],
];