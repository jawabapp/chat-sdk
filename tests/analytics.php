<?php

require_once __DIR__ . '/autoload.php';

use ChatSDK\Analytics\Events;

$campaign_id = uniqid();

$events = new Events($campaign_id);
$events->logEvent('landing-on-lp');
$events->logEvent('click-to-download', [
    'extra_param' => 'x'
]);
$events->logEvent('click-close');
$events->render();
