<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 12/2/18
 * Time: 9:09 AM
 */

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use ChatSDK\Facades\Config;
use ChatSDK\Channels\DeepLinksChannel;

Config::make([
    'app_token' => 'token.token.token.token'
]);

$topic = new \ChatSDK\Support\Topic();
$topic->generateTopic(1);

$subscription_link = DeepLinksChannel::generate_subscription_link(
    $topic,
    '+962799141272',
    1,
    'https://www.jawabkom.com/front/access/login_by_phone'
);

echo "\n";
echo $subscription_link;
echo "\n";