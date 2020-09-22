<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/14/18
 * Time: 9:54 AM
 */

require_once __DIR__ . '/autoload.php';

use ChatSDK\Facades\Config;
use ChatSDK\Channels\UserChannel;

Config::make(array(
    'service_token' => 'token.token.token.token',
    'service_endpoint' => 'http://'
));

$group = UserChannel::create(array(
    "category_id" => "service-category-id",
    "language" => "en"
));

print_r($group);
