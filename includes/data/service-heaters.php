<?php
return [
    'prices' => [
        ['badge' => '2кВт', 'value' => 'Воздушный 2 кВт',             'label' => 'heat_price1'],
        ['badge' => '4кВт', 'value' => 'Воздушный 4 кВт',             'label' => 'heat_price2'],
        ['badge' => 'Ж',    'value' => 'Жидкостный',                  'label' => 'heat_price3'],
        ['badge' => '+',    'value' => 'Монтаж оборудования клиента', 'label' => 'heat_price4'],
        ['badge' => 'S',    'value' => 'Диагностика / ремонт',        'label' => 'heat_price5'],
    ],

    'options' => [
        ['value' => 'Воздушный 2 кВт',             'label' => 'heat_opt1'],
        ['value' => 'Воздушный 4 кВт',             'label' => 'heat_opt2'],
        ['value' => 'Жидкостный',                  'label' => 'heat_opt3'],
        ['value' => 'Монтаж оборудования клиента', 'label' => 'heat_opt4'],
        ['value' => 'Диагностика / ремонт',        'label' => 'heat_opt5'],
    ],

    'steps' => [
        ['num' => '01', 't' => 'heat_step1_t', 'd' => 'heat_step1_d'],
        ['num' => '02', 't' => 'heat_step2_t', 'd' => 'heat_step2_d'],
        ['num' => '03', 't' => 'heat_step3_t', 'd' => 'heat_step3_d'],
        ['num' => '04', 't' => 'heat_step4_t', 'd' => 'heat_step4_d'],
    ],

    'services' => ['heat_srv1', 'heat_srv2', 'heat_srv3', 'heat_srv4', 'heat_srv5', 'heat_srv6'],
];