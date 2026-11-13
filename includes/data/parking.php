<?php
/**
 * Данные для parking.php.
 */
return [
    'icons' => [
        [
            'img' => 'https://cdn-icons-png.flaticon.com/512/1788/1788637.png',
            'alt' => 'Parking',
            'h'   => 'park_icon1_h',
            'p'   => 'park_icon1_p',
        ],
        [
            'img' => 'https://cdn-icons-png.flaticon.com/512/407/407775.png',
            'alt' => 'Warehouse',
            'h'   => 'park_icon2_h',
            'p'   => 'park_icon2_p',
        ],
        [
            'img' => 'https://cdn-icons-png.flaticon.com/512/1518/1518379.png',
            'alt' => 'Offices',
            'h'   => 'park_icon3_h',
            'p'   => 'park_icon3_p',
        ],
    ],

    'daily' => [
        ['label' => 'park_row1', 'price' => '45', 'unit' => 'day'],
        ['label' => 'park_row2', 'price' => '60', 'unit' => 'day'],
        ['label' => 'park_row3', 'price' => '75', 'unit' => 'day'],
    ],

    'monthly' => [
        ['label' => 'park_row1', 'price' => '700',  'unit' => 'month'],
        ['label' => 'park_row2', 'price' => '1200', 'unit' => 'month'],
        ['label' => 'park_row3', 'price' => '1500', 'unit' => 'month'],
    ],

    'special' => [
        ['label' => 'park_srow1', 'price' => '500',  'unit' => 'month'],
        ['label' => 'park_srow2', 'price' => '1000', 'unit' => 'month'],
        ['label' => 'park_srow3', 'price' => '1200', 'unit' => 'month'],
    ],

    'options' => [
        ['value' => 'Паркування',    'label' => 'park_opt1'],
        ['value' => 'Оренда складу', 'label' => 'park_opt2'],
        ['value' => 'Оренда офісу',  'label' => 'park_opt3'],
    ],
];