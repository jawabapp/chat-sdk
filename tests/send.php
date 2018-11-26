<?php 
    
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use ChatSDK\Facades\Config;
use ChatSDK\Channels\SendChannel;

Config::make(array(
    'app_token' => 'token.token.token.token',
));

SendChannel::send(790886, 'grp/srv-1/1_9_1543126263', 'text', 'i love you');