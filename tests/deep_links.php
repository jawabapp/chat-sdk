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

//if qoustion id exist in table
$topic->setTopic('grp/srv-2/45255077_5c1a63f832fca');
//$topic->generateTopic(45255077);

$subscription_link = DeepLinksChannel::generate_subscription_link(
    $topic,
    '+962799141272',
    1,
    'https://www.jawabkom.com/front/access/login_by_phone',
    array('aa'=>1,'bb'=>2)
);

//$chat_link = DeepLinksChannel::generate_chat_link(
//    $topic,
//    '+962799141272',
//    'https://www.jawabkom.com/front/access/login_by_phone'
//);
//
//$login_link = DeepLinksChannel::generate_login_link(
//    '+962799141272',
//    'https://www.jawabkom.com/front/access/login_by_phone'
//);

echo "\n";

echo $subscription_link;
echo "\n";

//echo $chat_link;
//echo "\n";

//echo $login_link;
//echo "\n";