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

////image
//'image_enabled' => empty($referrerMessage['image']['url']) ? false : true,
//'image' => empty($referrerMessage['image']['url']) ? "" : $referrerMessage['image']['url'],
//'height' => empty($referrerMessage['image']['height']) ? "" : $referrerMessage['image']['height'],
//'width' => empty($referrerMessage['image']['width']) ? "" : $referrerMessage['image']['width'],

echo "\n" . DeepLinksChannel::generate_one_to_one_chat_link(
        'https://www.jawabtaza.com/ar',
        'Qanah',
        [
            'language' => 'ar',
            'deep_link' => 'https://www.jawabtaza.com/ar/product/عنب-اخضر-مستورد-1-كجم/?v=fbe46383db39',
            'notification_title' => 'عنب اخضر مستورد 🍇🍇🍇',
            'button_title' => '🍇 button_title 🍇',
            'description' => 'عنب اخضر مستورد 🍇🍇🍇 description',
            'bolds' => [
                'expert' => false,
                'description' => false,
                'button' => false,
            ],
            'colors' => [
                'text_expert' => "#000000",
                'text_description' => "#000000",
                'text_button' => "#ffffff",
                'background' => "#ffffff",
                'button' => "#24db27",
            ],
            'expert' => [
                'name' => "",
                'image' => "",
                'title' => "",
                'subtitle' => "",
            ],
            'image' => [
                'url' => "https://www.jawabtaza.com/wp-content/uploads/2020/10/Green-Grapes-min.jpg",
                'height' => "",
                'width' => "",
            ],
        ],
        'test-web-uuid',
        [
            'utm_medium' => 'deep-link test',
        ]
    ) . "\n";

//echo "\n" . DeepLinksChannel::generate_account_link(
//        'https://trends.jawab.app/Qanah',
//        'Qanah',
//        [],
//        [
//            'utm_medium' => 'deep-link test',
//        ]
//    ) . "\n";

//echo "\n" . DeepLinksChannel::generate_login_link(
//        '+962799141272',
//        'https://www.jawabkom.com/front/access/login_by_phone'
//    ) . "\n";
//
//echo "\n" . DeepLinksChannel::generate_broadcast_link(
//        'https://playwin.app/en/login'
//        ,[],
//        [
//            'utm_source' => 'playwin',
//            'utm_medium' => 'popup',
//            'utm_campaign' => 'val_user_sub_id',
//            'utm_content' => json_encode([
//                'user_sub_id' => 'val_user_sub_id'
//            ]),
//        ]
//    ) . "\n";
//
//echo "\n" . DeepLinksChannel::generate_subscription_link(
//        $topic,
//        '+962799141272',
//        15,
//        'https://www.jawabkom.com/front/access/login_by_phone',
//        array(
//            'category' => 'Medical',
//            'expert_image' => 'https://www.gravatar.com/avatar/0debb6ee89fbc40a267cb6e13c613c52',
//            'expert_name' => 'Dr. Sulaiman Abd Al-Hadi',
//            'expert_title' => 'Licensed Doctor',
//            'expert_subtitle' => 'Answered: 19,783 - Rating: 86.21%',
//        )
//    ) . "\n";
//
//echo "\n" . DeepLinksChannel::generate_chat_link(
//        $topic,
//        '+962799141272',
//        'https://www.jawabkom.com/front/access/login_by_phone'
//    ) . "\n";


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
