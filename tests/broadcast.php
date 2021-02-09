<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 12/2/18
 * Time: 9:09 AM
 */

require_once __DIR__ . '/autoload.php';

use ChatSDK\Facades\Config;

Config::make([
    'app_token' => config('APP_TOKEN')
]);

$uuid = config('UUID');

\ChatSDK\Channels\BroadcastChannel::sendText($uuid, 'send me in slack ok if received');

//\ChatSDK\Channels\BroadcastChannel::sendImage($uuid, 'https://helpx.adobe.com/content/dam/help/en/stock/how-to/visual-reverse-image-search-v2_297x176.jpg');

//\ChatSDK\Channels\BroadcastChannel::sendLinkableImage($uuid, 'https://post.jawab.app/x9n6TcSqFCikyhcg9', 'https://helpx.adobe.com/content/dam/help/en/stock/how-to/visual-reverse-image-search-v2_297x176.jpg');

//\ChatSDK\Channels\BroadcastChannel::sendForceDeepLink($uuid, 'https://post.jawab.app/x9n6TcSqFCikyhcg9', 'xyz ForceDeepLink');

//\ChatSDK\Channels\BroadcastChannel::sendPremium($uuid, 'https://post.jawab.app/x9n6TcSqFCikyhcg9', 'xyz Premium', 'title', 'sub title', 'button', array('aaa', 'bbb', 'ccc'));

//\ChatSDK\Channels\BroadcastChannel::sendSubscription($uuid, 'https://post.jawab.app/x9n6TcSqFCikyhcg9', 'xyz Subscription', 'description', 'button');

//\ChatSDK\Channels\BroadcastChannel::sendSubscription($uuid, 'https://post.jawab.app/x9n6TcSqFCikyhcg9', 'xyz Subscription 2', 'description', 'button', array(
//    'image' => 'https://helpx.adobe.com/content/dam/help/en/stock/how-to/visual-reverse-image-search-v2_297x176.jpg',
//    'name' => 'Ibraheem Qanah',
//    'title' => 'Developer',
//    'subtitle' => 'rate: 90%',
//));
