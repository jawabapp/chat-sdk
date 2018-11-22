<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use ChatSDK\Facades\Config;
use ChatSDK\Channels\ReceiveChannel;

Config::make(array(
    'app_token' => 'token.token.token.token',
));

ReceiveChannel::receive(function($message, $sender) {

    echo json_encode($message);
    echo json_encode($sender);

},true);