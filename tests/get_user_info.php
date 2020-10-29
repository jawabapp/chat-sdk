<?php
require_once __DIR__ . '/autoload.php';

use ChatSDK\Facades\Config;

Config::make([
    'app_token' => config('APP_TOKEN')
]);

$uuid = config('UUID');

$user = \ChatSDK\Channels\UserChannel::info($uuid);

print_r($user);
