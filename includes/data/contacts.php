<?php
/**
 * Отделы с контактами.
 * phones[] — массив номеров. 'tg' = Telegram username без @
 *            (⚠ уточнить у заказчика; здесь выведено из email).
 * email — адрес для mailto.
 */
return [
    [
        'key'    => 'dept_log',
        'phones' => [
            ['tel' => '+380730779009', 'label' => '+380 73 077 90 09', 'tg' => 'apuslogist'],
        ],
        'email'  => 'apuslogist@gmail.com',
    ],
    [
        'key'    => 'dept_tech',
        'phones' => [
            ['tel' => '+380660339009', 'label' => '+380 66 033 90 09', 'tg' => 'apuslegatt'],
            ['tel' => '+380630339009', 'label' => '+380 63 033 90 09', 'tg' => 'apuslegatt'],
        ],
        'email'  => 'apuslegatt@gmail.com',
    ],
    [
        'key'    => 'dept_fin',
        'phones' => [
            ['tel' => '+380936483782', 'label' => '+380 93 648 37 82', 'tg' => 'apusbuhn'],
        ],
        'email'  => 'apusbuhn@gmail.com',
    ],
];