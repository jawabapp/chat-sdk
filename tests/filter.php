<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use ChatSDK\Channels\Product\Builder\Filter;

\ChatSDK\Facades\Config::make([
    'not_secure' => true,
    'host' => 'localhost:82',
    'app_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjEifQ.eyJpc3MiOiJodHRwOlwvXC9jaGF0Lmphd2FiLmFwcCIsImF1ZCI6Ikphd2Fia29tIiwianRpIjoiMSIsImlhdCI6MTU1MzE2MzMzMSwibmJmIjoxNTUzMTYzMzMxLCJleHAiOjE1Nzc4MzY4MDAsInVpZCI6MX0.aA8NGQYL2FTbWuctOdakyipnL-4Y0ysZaIjKkdgSaeerYXGxHlVb_b_YJfDfIvFjqN0OJNMEKgssMj8EGNmcbeV7tASB6JQ9U3GSde_TvBwhZTfTmau-SYPs8r5Wpk9znIfn-Gk49NDImmC1JXhJyT3tMIkkElmmKBfiSny7YTAXQ5Oiu1WKjkdIBCoFaIh4yiX1c6oNgctpvjFHEi8ULXSQVH3E88SAxTJG4oPfZDT9a5_Rb2VwsbPeoxMUQRabiQ-vKUyxl6YPDDtIaZPEb_YOHJOhGfizVKc_nicbrPlXKNmPygV-5YouqejAw0WBcUvbaJJ9oPCL2Vizg7UfXe0RlvQWmqmHbwCOAgvU8H6JQb8HNGNAgByiXk5isQcYe1LRsGT5ShSHDEfQvQEEgbXQ1GAzPe9WnQuJGxB_sM7CJ4gRggfklggF7dZtUfUFJtSF6iqid6EXwjhr4Hvhz4cQwqY640sRHhOxE0do9X0udCdlPkUnzVA7LslkzdAuV_S-CTugwVBhH-YirZVazkqotJopIRyOAbYgDUZmM3j8JLrkbqSm7U7XTkTni2aJMx7oH8S1eqcDnEn8YAMl9o6doNhpn0tVMcD_5UTgxphSc2HV7fv7kfhXDirEeNe__hC_jQraYPYOverqoyGoRCVtGlQksEQp7ooyvXYMZOM'
]);

Filter::checkBoxes('condition', 'Condition', [
    'new' => 'New',
    'used' => 'Used',
    'refurbished' => 'Refurbished'
]);

Filter::checkBoxes('stores', 'Stores', [
    'ebay' => 'Ebay',
    'amazon' => 'Amazon',
    'souq' => 'Souq',
    'jumia' => 'Jumia',
    'aliexpress' => 'Aliexpress',
]);

Filter::switchKey('free_shipping', 'Free Shipping only');

echo Filter::build();