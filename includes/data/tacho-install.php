<?php
/**
 * Данные для tacho-install.php.
 *  - brands_*       — опции селекта (optgroup).
 *  - prices         — { code: { 12: price, 24: price } }; отсутствующая комбинация = «не поддерживается».
 *  - reviews, steps — статичный контент, переводится через lang-ключи.
 */
return [
    'brands_trucks' => [
        'TATA', 'ISUZU', 'ZIL', 'MAZ', 'KAMAZ', 'URAL', 'HYUNDAI',
        'MERCEDES', 'KRAZ', 'RENAULT', 'MAN', 'FOTON', 'BAZ', 'GAZ',
        'SINOTRUK', 'INTERCARGOTRUCK', 'DAF', 'VOLVO',
    ],
    'brands_vans' => ['SPRINTER', 'CRAFTER', 'IVECO_DAILY', 'TRANSIT'],

    'brand_labels' => [
        'ZIL' => 'ЗИЛ', 'MAZ' => 'МАЗ', 'KAMAZ' => 'КАМАЗ', 'URAL' => 'УРАЛ',
        'KRAZ' => 'КРАЗ', 'BAZ' => 'БАЗ', 'GAZ' => 'ГАЗ',
        'SPRINTER' => 'Mercedes Sprinter', 'CRAFTER' => 'VW Crafter',
        'IVECO_DAILY' => 'Iveco Daily', 'TRANSIT' => 'Ford Transit',
        'VOLVO' => 'Volvo',
    ],

    'prices' => [
        'TATA'           => ['12' => 40000, '24' => 38000],
        'ISUZU'          => ['12' => 38000, '24' => 36000],
        'ZIL'            => ['12' => 40000, '24' => 38000],
        'MAZ'            => ['24' => 38000],
        'KAMAZ'          => ['24' => 38000],
        'URAL'           => ['24' => 38000],
        'HYUNDAI'        => ['12' => 40000, '24' => 38000],
        'MERCEDES'       => ['12' => 42000, '24' => 40000],
        'KRAZ'           => ['24' => 38000],
        'RENAULT'        => ['12' => 42000, '24' => 40000],
        'MAN'            => ['24' => 38000],
        'FOTON'          => ['12' => 40000, '24' => 38000],
        'BAZ'            => ['12' => 40000, '24' => 38000],
        'GAZ'            => ['12' => 40000, '24' => 38000],
        'SINOTRUK'       => ['12' => 42000, '24' => 40000],
        'INTERCARGOTRUCK'=> ['12' => 40000, '24' => 38000],
        'DAF'            => ['24' => 38000],
        'VOLVO'          => ['24' => 38000],
        'SPRINTER'       => ['12' => 42000],
        'CRAFTER'        => ['12' => 42000],
        'IVECO_DAILY'    => ['12' => 42000, '24' => 40000],
        'TRANSIT'        => ['12' => 42000],
    ],

    'steps' => [
        ['num' => '01', 't' => 'tins_step1_t', 'd' => 'tins_step1_d'],
        ['num' => '02', 't' => 'tins_step2_t', 'd' => 'tins_step2_d'],
        ['num' => '03', 't' => 'tins_step3_t', 'd' => 'tins_step3_d'],
        ['num' => '04', 't' => 'tins_step4_t', 'd' => 'tins_step4_d'],
    ],

    'reviews' => [
        ['name' => 'tins_rev1_name', 'text' => 'tins_rev1_text'],
        ['name' => 'tins_rev2_name', 'text' => 'tins_rev2_text'],
        ['name' => 'tins_rev3_name', 'text' => 'tins_rev3_text'],
        ['name' => 'tins_rev4_name', 'text' => 'tins_rev4_text'],
        ['name' => 'tins_rev5_name', 'text' => 'tins_rev5_text'],
        ['name' => 'tins_rev6_name', 'text' => 'tins_rev6_text'],
    ],
];