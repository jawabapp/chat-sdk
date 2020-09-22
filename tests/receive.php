<?php

require_once __DIR__ . '/autoload.php';

use ChatSDK\Facades\Config;
use ChatSDK\Channels\Mqtt\ReceiveChannel;

Config::make([
    'app_token' => config('APP_TOKEN')
]);

ReceiveChannel::receive(function($message, $sender) {

    echo json_encode($message);
    echo json_encode($sender);

},function($message, $sender) {

    echo json_encode($message);
    echo json_encode($sender);

},true);
