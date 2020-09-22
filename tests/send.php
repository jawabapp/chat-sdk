<?php

require_once __DIR__ . '/autoload.php';

use ChatSDK\Facades\Config;
use ChatSDK\Channels\Mqtt\SendChannel;

Config::make([
    'app_token' => config('APP_TOKEN')
]);

SendChannel::send(790886, 'grp/srv-1/1_9_1543126263', 'text', 'i love you');
