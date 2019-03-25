<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use ChatSDK\Channels\Product\Builder\Filter;

\ChatSDK\Facades\Config::make([
    'not_secure' => true,
    'host' => 'localhost:82',
    'app_token' => 'token.token.token.token'
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
