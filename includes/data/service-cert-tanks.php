<?php
return [
    'methods' => [
        ['t' => 'tanks_m1_t', 'd' => 'tanks_m1_d'],
        ['t' => 'tanks_m2_t', 'd' => 'tanks_m2_d'],
        ['t' => 'tanks_m3_t', 'd' => 'tanks_m3_d'],
        ['t' => 'tanks_m4_t', 'd' => 'tanks_m4_d'],
    ],

    'gallery' => [
        ['src' => 'img/public/adr/dscf9227_1.webp',  'alt' => 'ADR fuel tank'],
        ['src' => 'img/public/adr/dscf9243_1.webp',  'alt' => 'ADR chemical tank'],
        ['src' => 'img/public/adr/dscf9245_1_1.webp','alt' => 'ADR gas tank'],
    ],

    'types' => [
        'tanks_type1',
        'tanks_type2',
        'tanks_type3',
        'tanks_type4',
    ],

    'results' => [
        'tanks_res1',
        'tanks_res2',
        'tanks_res3',
        'tanks_res4',
    ],

    'options' => [
        ['value' => 'Сертифікація ADR цистерни',      'label' => 'tanks_opt_cert'],
        ['value' => 'Періодична перевірка ADR',       'label' => 'tanks_opt_periodic'],
        ['value' => 'Проміжна перевірка ADR',         'label' => 'tanks_opt_intermediate'],
        ['value' => 'Позапланова перевірка ADR',      'label' => 'tanks_opt_unscheduled'],
        ['value' => 'Гідравлічні випробування',       'label' => 'tanks_opt_hydro'],
        ['value' => 'Консультація ADR',               'label' => 'tanks_opt_consult'],
    ],
];