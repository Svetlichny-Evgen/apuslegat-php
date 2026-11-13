<?php
/**
 * Данные для radio-install.php.
 *  - prices: первый элемент массива [label_key, price_key] — список;
 *            price_key может быть 'tins_price_free' для «Бесплатно».
 *  - options: value — на украинском (для Telegram), key — в translations.
 *  - steps: num + t (title key) + d (desc key).
 */
return [
    'options' => [
        ['value_uk' => 'Встановлення рації',           'label' => 'rad_opt_install'],
        ['value_uk' => 'Консультація (безкоштовно)',   'label' => 'rad_opt_consult'],
        ['value_uk' => 'Придбання комплекту рації',    'label' => 'rad_opt_buy'],
    ],

    'prices' => [
        ['rad_price_row1',      'rad_price_val1'],
        ['rad_price_row2',      'rad_price_val2'],
        ['rad_price_row3',      'rad_price_val3'],
        ['rad_price_row4',      'rad_price_val4'],
        ['rad_price_row5',      'rad_price_val5'],
        ['rad_price_row6',      'rad_price_val6'],
        ['rad_price_row7',      'rad_price_val7'],
        ['rad_price_row8',      'rad_price_val8'],
        ['rad_price_row9',      'rad_price_free'],
    ],

    'steps' => [
        ['num' => '01', 't' => 'rad_step1_t', 'd' => 'rad_step1_d'],
        ['num' => '02', 't' => 'rad_step2_t', 'd' => 'rad_step2_d'],
        ['num' => '03', 't' => 'rad_step3_t', 'd' => 'rad_step3_d'],
        ['num' => '04', 't' => 'rad_step4_t', 'd' => 'rad_step4_d'],
    ],
];