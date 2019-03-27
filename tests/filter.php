<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use ChatSDK\Channels\Product\Builder\Filter;
use ChatSDK\Channels\Product\Builder\Label;
use ChatSDK\Channels\Product\Builder\Options;

\ChatSDK\Facades\Config::make([
    'not_secure' => true,
    'host' => 'localhost:82',
    'app_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjEifQ.eyJpc3MiOiJodHRwOlwvXC9jaGF0Lmphd2FiLmFwcCIsImF1ZCI6Ikphd2Fia29tIiwianRpIjoiMSIsImlhdCI6MTU1MzE2MzMzMSwibmJmIjoxNTUzMTYzMzMxLCJleHAiOjE1Nzc4MzY4MDAsInVpZCI6MX0.aA8NGQYL2FTbWuctOdakyipnL-4Y0ysZaIjKkdgSaeerYXGxHlVb_b_YJfDfIvFjqN0OJNMEKgssMj8EGNmcbeV7tASB6JQ9U3GSde_TvBwhZTfTmau-SYPs8r5Wpk9znIfn-Gk49NDImmC1JXhJyT3tMIkkElmmKBfiSny7YTAXQ5Oiu1WKjkdIBCoFaIh4yiX1c6oNgctpvjFHEi8ULXSQVH3E88SAxTJG4oPfZDT9a5_Rb2VwsbPeoxMUQRabiQ-vKUyxl6YPDDtIaZPEb_YOHJOhGfizVKc_nicbrPlXKNmPygV-5YouqejAw0WBcUvbaJJ9oPCL2Vizg7UfXe0RlvQWmqmHbwCOAgvU8H6JQb8HNGNAgByiXk5isQcYe1LRsGT5ShSHDEfQvQEEgbXQ1GAzPe9WnQuJGxB_sM7CJ4gRggfklggF7dZtUfUFJtSF6iqid6EXwjhr4Hvhz4cQwqY640sRHhOxE0do9X0udCdlPkUnzVA7LslkzdAuV_S-CTugwVBhH-YirZVazkqotJopIRyOAbYgDUZmM3j8JLrkbqSm7U7XTkTni2aJMx7oH8S1eqcDnEn8YAMl9o6doNhpn0tVMcD_5UTgxphSc2HV7fv7kfhXDirEeNe__hC_jQraYPYOverqoyGoRCVtGlQksEQp7ooyvXYMZOM'
]);

$filter = new Filter();

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
        'amazon' => Label::make([
            'ar' => 'امازون',
            'en' => 'Amazon',
        ]),
        'souq' => Label::make([
            'ar' => 'سوق كوم',
            'en' => 'Souq',
        ]),
        'jumia' => Label::make([
            'ar' => 'جوميا',
            'en' => 'Jumia',
        ]),
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

$filter->build();