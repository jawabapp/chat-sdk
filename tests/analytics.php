<?php

require_once __DIR__ . '/autoload.php';

use ChatSDK\Analytics\Events;

$uuid = uniqid();

$events = new Events($uuid);
$events->logEvent('test-sdk-library');
$events->render();
