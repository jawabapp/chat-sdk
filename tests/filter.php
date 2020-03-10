<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use ChatSDK\Channels\Product\Builder\Filters;
use ChatSDK\Channels\Product\Builder\Label;
use ChatSDK\Channels\Product\Builder\Option;
use ChatSDK\Channels\Product\Builder\Options;

//\ChatSDK\Facades\Config::make([
//    'not_secure' => true,
//    'host' => 'localhost:82',
//    'app_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjEifQ.eyJpc3MiOiJodHRwOlwvXC9jaGF0Lmphd2FiLmFwcCIsImF1ZCI6Ikphd2Fia29tIiwianRpIjoiMSIsImlhdCI6MTU1MzE2MzMzMSwibmJmIjoxNTUzMTYzMzMxLCJleHAiOjE1Nzc4MzY4MDAsInVpZCI6MX0.aA8NGQYL2FTbWuctOdakyipnL-4Y0ysZaIjKkdgSaeerYXGxHlVb_b_YJfDfIvFjqN0OJNMEKgssMj8EGNmcbeV7tASB6JQ9U3GSde_TvBwhZTfTmau-SYPs8r5Wpk9znIfn-Gk49NDImmC1JXhJyT3tMIkkElmmKBfiSny7YTAXQ5Oiu1WKjkdIBCoFaIh4yiX1c6oNgctpvjFHEi8ULXSQVH3E88SAxTJG4oPfZDT9a5_Rb2VwsbPeoxMUQRabiQ-vKUyxl6YPDDtIaZPEb_YOHJOhGfizVKc_nicbrPlXKNmPygV-5YouqejAw0WBcUvbaJJ9oPCL2Vizg7UfXe0RlvQWmqmHbwCOAgvU8H6JQb8HNGNAgByiXk5isQcYe1LRsGT5ShSHDEfQvQEEgbXQ1GAzPe9WnQuJGxB_sM7CJ4gRggfklggF7dZtUfUFJtSF6iqid6EXwjhr4Hvhz4cQwqY640sRHhOxE0do9X0udCdlPkUnzVA7LslkzdAuV_S-CTugwVBhH-YirZVazkqotJopIRyOAbYgDUZmM3j8JLrkbqSm7U7XTkTni2aJMx7oH8S1eqcDnEn8YAMl9o6doNhpn0tVMcD_5UTgxphSc2HV7fv7kfhXDirEeNe__hC_jQraYPYOverqoyGoRCVtGlQksEQp7ooyvXYMZOM'
//]);

\ChatSDK\Facades\Config::make([
    // 'not_secure' => true,
    'host' => 'chat.jawab.app',
    'app_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjEifQ.eyJpc3MiOiJodHRwOlwvXC9jaGF0Lmphd2FiLmFwcCIsImF1ZCI6Imphd2Fia29tIiwianRpIjoiMSIsImlhdCI6MTU2NDQwNjkxNywibmJmIjoxNTY0NDA2OTE3LCJleHAiOjE3MzU2ODk2MDAsInVpZCI6MX0.BzbF4LBtuNL5TddjYv8ioA3Jq98aO2EQoduXHIIYn1xpX0VbY7-SoD1-GQlIUBhobK_Ss1L0Tpw_5L0T0xAnmQ1HNn9kq4x1eShObI-SwZcpV60LWHiVh0Vnl7GyEjopUxwiKHlu8NAj5ERH-wlKz4EDZhUtLBI1IUzVa9zhcfE6y3uMwfqNBVif-rwTySzt-XegmyVMWbxeUVK2D_F3DYJsIjlbHSSoyPURyJAsteMNu-__7BF_PXrgw8wK2ISiIr8e10NkblgfUvPJWLVu9H0kueDd90m9ezU3_ikQug575JYGK8Tnl-0Ia0omDDETK2xaeI4kx4BB2oeb36V1lD-9EBp2Gdrc7S0yhFK25tO1904xsvFfY31gDUSrEvYhqTX9t5WJiPRoObq59xsnIT5IvqEuXo1c3YWoiZB35p2hapZlrP0FSFDuzRqANT3btMaunISdHAGCDjFep1wC6narVY-g7IFtxycBp-5z95JUet6iQMzKAOiodY5wowvQFGQeL3sprcu5RvVbfNazbM2X9Stfyy8RCAx61karTe-37c67yarEdMsrA7_MEb8AHPbpkYiwWB1AFPBQTQtlL_2BZWn-PPKcoebDm8L305NkTDGVYO7fAYzK_7tY3K0D-nE5PKOTvsiZjP-BEiP1rAfLUB9Dd6aD0Uam_y1U_ns'
]);

$filter = new Filters();

$filter->rangeSelector(
    'from_price', 0,
    'to_price', 2000,
    Label::make([
        'ar' => 'نطاق السعر',
        'en' => 'Price range',
    ])
);

$filter->checkBoxes(
    'condition',
    Label::make([
        'ar' => 'الشروط',
        'en' => 'Condition',
    ]),
    Options::make([
        'new' => Label::make([
            'ar' => 'جديد',
            'en' => 'New',
        ]),
        'used' => Label::make([
            'ar' => 'مستخدم',
            'en' => 'Used',
        ]),
        'refurbished' => Label::make([
            'ar' => 'تم تجديده',
            'en' => 'Refurbished',
        ])
    ])
);

$filter->checkBoxes(
    'stores',
    Label::make([
        'ar' => 'المتاجر',
        'en' => 'Stores',
    ]),
    Options::make([
        'ebay' => Label::make([
            'ar' => 'إيباي',
            'en' => 'Ebay',
        ]),
        'amazon' => Option::make(
            Label::make([
                'ar' => 'امازون',
                'en' => 'Amazon',
            ]),
            ['country' => ['ts', 'bs']]
        ),
        'souq' => Label::make([
            'ar' => 'سوق كوم',
            'en' => 'Souq',
        ]),
        'jumia' => Option::make(
            Label::make([
                'ar' => 'جوميا',
                'en' => 'Jumia',
            ]),
            ['country' => ['ts']]
        ),
        'aliexpress' => Label::make([
            'ar' => 'علي إكسبريس',
            'en' => 'Aliexpress',
        ]),
    ])
);

$filter->switchKey('free_shipping', Label::make([
    'ar' => 'الشحن مجاني',
    'en' => 'Free Shipping only',
]));

$config_name = $filter->build(true);

error_log($config_name);
