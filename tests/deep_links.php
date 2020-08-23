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

//Config::make([
//    'host' => 'localhost:82',
//    'not_secure' => true,
//    'app_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjUifQ.eyJpc3MiOiJodHRwOlwvXC9jaGF0Lmphd2FiLmFwcCIsImF1ZCI6IldlYiBWaWV3IFRlc3QiLCJqdGkiOiI1IiwiaWF0IjoxNTk3MDY3NTY0LCJuYmYiOjE1OTcwNjc1NjQsImV4cCI6MTc2NzEzOTIwMCwidWlkIjo1fQ.V8C42LlJHUjVuMRlHfzVFQY-aY8A619tLT5ItJQGJhGySjpxffiCyzDWCKeXkeEVhvEA4VnMXhxyqILLGnpsB-1mxupWIGfpCvMIaKYnodaz-a7F5_QnrG06PooEHudmv3kdDA6TR0qHbFnFo8YViIaota8Y5eofsy1h8f_NAV8X-79X-fUPqOS9sfrEQhkp45J54yMLM1s1fqAY6SSqoRh1PDRxnph2NzRcAX70uYwBNdpfysp_e-yBO7rC-IhqXKxWr6VSGKaUuHwQjjjzrOm0xDtXDAcYQrBFXyXHc6_uhptCnsiW9ub5qe21MQ3GqMAXWjIIy2n7VLnH8UyzHvUA8a2F9ww7xG5oGmf9GW_MGPlHpxkiUIluTC_04XrPbQG-JAEQ2OK1sVysP7jQCjN2fAdUJ6S-7EBAUDOYqvLVj1J8r8nn_8SxEcONDp16y08x-bFl1HKcMEl8yVgrf4we2mn1BftwSuGulNGr68xeaYuJERr9-0cBd2CafeLcQFjBzFdCiFdThPD3tJ7NeyZT6g3oXL-I-N5xEvT1qrNE-veX9mqnd-PZKXi6BJb7wDszUDiCnVOBT6di1adeZtECIuhSHlhXL-E2a6OwuSaTMBvkiypz9DnliSHzmYPy_DMNglRnl7rrB9sxz0q0mnSiHn2bZtGAY0IVy-vMOpw'
//]);

Config::make([
    'app_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjYifQ.eyJpc3MiOiJodHRwOlwvXC9jaGF0Lmphd2FiLmFwcCIsImF1ZCI6InBsYXl3aW4iLCJqdGkiOiI2IiwiaWF0IjoxNTkyODIxMjQ0LCJuYmYiOjE1OTI4MjEyNDQsImV4cCI6MTc2NzEzOTIwMCwidWlkIjo2fQ.KUpvpkal_OdeIjFZBnOwnnpH5KC5z4HQ-GuQnujiQTTEuNi7RmG8kvhMVtAVB2LP-DVjDox03Ok1gZIqBeh6VLLee2a_oSvCzu6au2IZWVJcmnaLWusy6b4nF9f1CRb3B7QaHD5Dou97vUwmuc4UuH0vGBp7lcAH2xc7aBcs8_Fz0rZNWRDyUrpbrBMjRtthZUH2euAa5S2AaKC_N9xvvhSMD40MV0hfHSBW361ZXFrObm-oUsIJZERyTYvDz1M-0g53jzp0mnlL4nb_RuFG6J52ze8p9_F-73KNuKx0-FeJV6QmHw8ivRju5IVZuLLTWZ_xSryTBIu_2hK0e6sqIbgPQBxV_MhNoAlHadPvacl3F9Iz26zPb79eJRlC5vj_mTK9OYczqBZ1AFxxT32scE9bFdSpXZZE85SQ_SQF-c7a1leLrHL7C53ULoE2fMHD9L1eeohF0_ynau2iiidOuFGyesnnxRj0coGXaJI2q2KFKd4UCCnxxX6HyGRIRrubphEM0FFGaWf5gpNccrC-FtqTaE-OE_-hgF5mftX1uhl9x2uoFaIVwW1I8y5pE6CM-LD-B6cID3GNGy_AL3FyRIP7-8J-rNlKUcsdxKNK27JihgzTEXTCzsMbdkF9-e9up0BvWOzLrV_ofc0DfhDixxgZhsg4yNJNS8pybKA8258'
]);

//\ChatSDK\Channels\BroadcastChannel::sendText('58329AB3-614A-41E6-8B6D-6FC724784DA8', 'test');
//\ChatSDK\Channels\BroadcastChannel::sendImage('58329AB3-614A-41E6-8B6D-6FC724784DA8', 'https://helpx.adobe.com/content/dam/help/en/stock/how-to/visual-reverse-image-search-v2_297x176.jpg');
//\ChatSDK\Channels\BroadcastChannel::sendLinkableImage('58329AB3-614A-41E6-8B6D-6FC724784DA8', 'https://post.jawab.app/x9n6TcSqFCikyhcg9', 'https://helpx.adobe.com/content/dam/help/en/stock/how-to/visual-reverse-image-search-v2_297x176.jpg');
//\ChatSDK\Channels\BroadcastChannel::sendForceDeepLink('58329AB3-614A-41E6-8B6D-6FC724784DA8', 'https://post.jawab.app/x9n6TcSqFCikyhcg9');
//\ChatSDK\Channels\BroadcastChannel::sendPremium('58329AB3-614A-41E6-8B6D-6FC724784DA8', 'https://post.jawab.app/x9n6TcSqFCikyhcg9', 'title', 'sub title', 'button', array('aaa', 'bbb', 'ccc'));
//\ChatSDK\Channels\BroadcastChannel::sendSubscription('58329AB3-614A-41E6-8B6D-6FC724784DA8', 'https://post.jawab.app/x9n6TcSqFCikyhcg9', 'description', 'button');
\ChatSDK\Channels\BroadcastChannel::sendSubscription('58329AB3-614A-41E6-8B6D-6FC724784DA8', 'https://post.jawab.app/x9n6TcSqFCikyhcg9', 'description', 'button', array(
    'image' => 'https://helpx.adobe.com/content/dam/help/en/stock/how-to/visual-reverse-image-search-v2_297x176.jpg',
    'name' => 'Ibraheem Qanah',
    'title' => 'Developer',
    'subtitle' => 'rate: 90%',
));

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
//
////if qoustion id exist in table
////$topic->setTopic('grp/srv-1/45255077_5c1a63f832fca');
//$topic->generateTopic(45255077);
//
//$auth_subscription_link = DeepLinksChannel::generate_shortcut_subscription_link(
//    $topic,
//    '+',
//    0,
//    6,
//    'https://www.jawabkom.com/front/access/login_by_phone',
//    array(
//        'category' => 'Medical',
//        'expert_image' => 'https://myblue.bluecrossma.com/sites/g/files/csphws1086/files/inline-images/Doctor%20Image%20Desktop.png',
//        'expert_name' => 'Dr. Sulaiman Abd Al-Hadi',
//        'expert_title' => 'Licensed Doctor',
//        'expert_subtitle' => 'Answered: 19,783 - Rating: 86.21%',
//    )
//);

//$subscription_link = DeepLinksChannel::generate_subscription_link(
//    $topic,
//    '+962799141272',
//    1,
//    'https://www.jawabkom.com/front/access/login_by_phone',
//    array(
//        'category' => 'Medical',
//        'expert_image' => 'https://myblue.bluecrossma.com/sites/g/files/csphws1086/files/inline-images/Doctor%20Image%20Desktop.png',
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
//$login_link = DeepLinksChannel::generate_login_link(
//    '+962799141272',
//    'https://www.jawabkom.com/front/access/login_by_phone'
//);

//echo "\n";
//
//echo $auth_subscription_link;
//echo "\n";

//echo $subscription_link;
//echo "\n";

//echo $chat_link;
//echo "\n";

//echo $login_link;
//echo "\n";
