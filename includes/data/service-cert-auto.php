<?php
return [
    'car_types' => [
        ['class' => 'bg-eu',    'title' => 'cert_type_eu',    'price' => 'cert_price_eu'],
        ['class' => 'bg-usa',   'title' => 'cert_type_usa',   'price' => 'cert_price_usa'],
        ['class' => 'bg-china', 'title' => 'cert_type_asia',  'price' => 'cert_price_asia'],
        ['class' => 'bg-gbo',   'title' => 'cert_type_gbo',   'price' => 'cert_price_gbo'],
        ['class' => 'bg-truck', 'title' => 'cert_type_mod',   'price' => 'cert_price_mod'],
    ],

    'requirements' => [
        ['h' => 'cert_req_light_h',  'p' => 'cert_req_light_p'],
        ['h' => 'cert_req_optics_h', 'p' => 'cert_req_optics_p'],
        ['h' => 'cert_req_gbo_h',    'p' => 'cert_req_gbo_p'],
        ['h' => 'cert_req_tint_h',   'p' => 'cert_req_tint_p'],
    ],

    'docs' => [
        'cert_doc1',
        'cert_doc2',
        'cert_doc3',
        'cert_doc4',
    ],

    'options' => [
        ['value' => 'Сертифікація авто з Європи',      'label' => 'cert_opt_eu'],
        ['value' => 'Сертифікація авто з США/Канади',  'label' => 'cert_opt_usa'],
        ['value' => 'Сертифікація авто з Китаю/Кореї', 'label' => 'cert_opt_asia'],
        ['value' => 'Сертифікація ГБО',                'label' => 'cert_opt_gbo'],
        ['value' => 'Переобладнання авто',             'label' => 'cert_opt_mod'],
        ['value' => 'Консультація',                    'label' => 'cert_opt_consult'],
    ],
];