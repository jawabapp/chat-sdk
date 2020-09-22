<?php

require_once __DIR__ . '/autoload.php';

use ChatSDK\Channels\Group\GroupInfoChannel;
use ChatSDK\Facades\Config;

Config::make([
    'app_token' => config('APP_TOKEN')
]);

GroupInfoChannel::update([
    'topic' => 'test-topic',
    'name' => 'test name',
    'avatar' => 'https://www.jawabkom.com/upload/img/user/2014/07/365616_1455729643.png',
    'description' => null,
]);
