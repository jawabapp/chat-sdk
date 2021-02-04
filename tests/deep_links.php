<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 12/2/18
 * Time: 9:09 AM
 */

require_once __DIR__ . '/autoload.php';

use ChatSDK\Facades\Config;
use ChatSDK\Channels\DeepLinksChannel;

Config::make([
    'app_token' => config('APP_TOKEN')
]);

//Config::make([
//    'host' => 'localhost:82',
//    'not_secure' => true,
//    'app_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjEifQ.eyJpc3MiOiJodHRwOlwvXC9jaGF0Lmphd2FiLmFwcCIsImF1ZCI6Ikphd2Fia29tIiwianRpIjoiMSIsImlhdCI6MTYxMDk2NzQxNiwibmJmIjoxNjEwOTY3NDE2LCJleHAiOjE3MzU2ODk2MDAsInVpZCI6MX0.bHKlriARgyQda0185_zs0qH5YMzYKTpCUD1sblHLfb2w_JfubrT0FU7MbGnHxrXekPbFe7IDUTe39isIeTCKg0CqLzRDdKCcj8l6qjI3J8Gvyiw_Py3qp8HALGCCAi-i-6CM5CjFsNyeDUyea9y8nrfDxi2_sI52erxPN1aQxN3jWaaDxS62iKMrODlvw6EgkKcR7pHa8h2RRBt1_u5dhhI09d_8g820hct8KNPBLhDmdr8mEv-kX4mLSVpiIoU035j7hl8rax4zgUlh_0W9yBVXo4_WqDFnpTJcSTCaCGMAZweD5ucwdvidF8-wfYUryuF96CoO5xWAxL0LskRYtmI0LBFBVTBD3mEQs9p9sYMmwKPhXkLRg_xLSzUoDuLDNHNKVmO2J2BpJ01VyeYKdTiEi0Cg4XRzdCIyF8Uwp46w_v78DZ0z9jgKpcNpX6pw8ENfbC93BA0TPDOQ_Fl9CUFVWtJmwUBIajWuc7z-2g3vyEoNvdUNQPXs_IEThm7KweN26tk4iWuR8tTf7rtDvQiPOa1WFwIy_uk1psuM2r8fQ7sSceMj9sb2zJLdqzf_jRduPxOJuIeWNMlXokBC7ZxR5Q3gn5Rvaz3zP8xFxIxRMMjvdy82ukZME-09-v-FTL8-wp66L11xLpm5zQVFJbZjrl-gaIcQLsZTqggc6Xw'
//]);

//$webview_link = DeepLinksChannel::generate_broadcast_link(
//    'https://playwin.app/en/login'
////    ,[],
////    [
////        'utm_source' => 'playwin',
////        'utm_medium' => 'popup',
////        'utm_campaign' => 'val_user_sub_id',
////        'utm_content' => json_encode([
////            'user_sub_id' => 'val_user_sub_id'
////        ]),
////    ]
//);
//
//echo "\n";
//echo $webview_link;
//echo "\n";

//$topic = new \ChatSDK\Support\Topic();

//if qoustion id exist in table
//$topic->setTopic('grp/srv-1/45255077_5c1a63f832fca');
//$topic->generateTopic(82349093);
//
//$auth_subscription_link = DeepLinksChannel::generate_shortcut_subscription_link(
//    $topic,
//    '+',
//    0,
//    6,
//    'https://www.jawabkom.com/front/access/login_by_phone',
//    array(
//        'category' => 'Medical',
//        'expert_image' => 'https://www.gravatar.com/avatar/0debb6ee89fbc40a267cb6e13c613c52',
//        'expert_name' => 'Dr. Sulaiman Abd Al-Hadi',
//        'expert_title' => 'Licensed Doctor',
//        'expert_subtitle' => 'Answered: 19,783 - Rating: 86.21%',
//    )
//);

//$subscription_link = DeepLinksChannel::generate_subscription_link(
//    $topic,
//    '+962799141272',
//    15,
//    'https://www.jawabkom.com/front/access/login_by_phone',
//    array(
//        'category' => 'Medical',
//        'expert_image' => 'https://www.gravatar.com/avatar/0debb6ee89fbc40a267cb6e13c613c52',
//        'expert_name' => 'Dr. Sulaiman Abd Al-Hadi',
//        'expert_title' => 'Licensed Doctor',
//        'expert_subtitle' => 'Answered: 19,783 - Rating: 86.21%',
//    )
//);

//$chat_link = DeepLinksChannel::generate_chat_link(
//    $topic,
//    '+962799141272',
//    'https://www.jawabkom.com/front/access/login_by_phone'
//);
//
$login_link = DeepLinksChannel::generate_login_link(
    '+962799141272',
    'https://www.jawabkom.com/front/access/login_by_phone'
);

//echo "\n";
//
//echo $auth_subscription_link;
//echo "\n";

//echo $subscription_link;
//echo "\n";

//echo $chat_link;
//echo "\n";

echo $login_link;
echo "\n";
