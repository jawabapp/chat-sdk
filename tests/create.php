<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/14/18
 * Time: 9:54 AM
 */

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use ChatSDK\Facades\Config;
use ChatSDK\Channels\GroupChannel;

Config::make(array(
    'service_token' => 'token.token.token.token',
    'service_endpoint' => 'http://'
));

$group = GroupChannel::create(array(
    "account_id" => "sender-account-id",
    "category_id" => "service-category-id",
    "category_name" => "Doctor",
    "category_image" => "http://"
));

print_r($group);