<?php 
    
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use ChatSDK\Facades\Config;
use ChatSDK\Channels\SendChannel;

Config::make(array(
    'app_token' => 'token.token.token.token',
));

SendChannel::send('test', 1);