<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use ChatSDK\Channels\Group\GroupInfoChannel;
use ChatSDK\Facades\Config;

Config::make(array(
    'app_token' => 'token.token.token.token',
));

GroupInfoChannel::update([
    'topic' => 'test-topic',
    'name' => 'test name',
    'avatar' => 'https://www.jawabkom.com/upload/img/user/2014/07/365616_1455729643.png',
    'description' => null,
]);
