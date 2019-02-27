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
    'app_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjIifQ.eyJpc3MiOiJodHRwOlwvXC9jaGF0Lmphd2FiLmFwcCIsImF1ZCI6InN0YWdpbmcgamF3YWJrb20iLCJqdGkiOiIyIiwiaWF0IjoxNTQ2NDM2MzE3LCJuYmYiOjE1NDY0MzYzMTcsImV4cCI6MTU3NzgzNjgwMCwidWlkIjoyfQ.MVP3St47eXHncn_BHTAutNmPRkpi5hzio2ciMYlMO262xJH7DsMHLOvED7P8zd2WASehiPaVy7Sd1qkfmOAMYu3-d_Ok2Hpb2z08CMoBiLmNbQ6MfJMV5sP6FyI3U1Sr9wrdN6Vhu785RWQNL3v55N4EDiS_mG7030GHZ48t92CoDQ6a-lchcYyFNwn208_lCAgj30pk7SZIYOJMoTwglIsfE5R1Isb0gfzr30NxHUs0QNBeR8xYyRj0ljZJDuTuuWNzcaXUr0SvuyPixD1_CEeoU-gwsgLp5hbq_GYUN10sqgEct82kQqUYPD48RN-BBAM-VIsygrKvXysWuikKJMcV86xkPieRrtt-so2M2aKY7YnxZAJotFW8fNowcYyjCN47G9Tj4lRxscDTzxC4quf_n8D6vQ9RYEQsFyVW-GoYkm3XqYlhliJQROoR9JVMPhpJfWmtY8yTxUQVQYCsFAS47Lob_rGItNdlJ4YAlxXipHM8xvbgHGYDc5UKNMEpdVsG76Rh89RrZJ6TN8lCqDaGaVzJ40dCmE6ZRggRHdGpEV4le6hFNWpKTL8U98WfOyKuaEFMWgG0EMTmlDP-3pxrG3hDc1N64IqvkCD8ry6SYCRH4s2e6slX0XUnHY0Xy2X9plE-RBWnWa52Jh3pSV_pKihA2I965wKC6vsvAhA'
    //'app_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjEifQ.eyJpc3MiOiJodHRwOlwvXC9jaGF0Lmphd2FiLmFwcCIsImF1ZCI6Imphd2Fia29tIiwianRpIjoiMSIsImlhdCI6MTU0NDM1NDE1MiwibmJmIjoxNTQ0MzU0MTUyLCJleHAiOjE1NDYzMDA4MDAsInVpZCI6MX0.Q3UmZaMyVM03qY6Tm193C_T9mFtYYqDxGNU1k37xNi5_W3Wa9LFDst8qP-h3K-i9eOyODYKHra6DrxVs3hCtbVN32cQ-wm4KyNMFrAXW2y9ufmThfonZSa40nmAdGaF8McL6PEu4fKPRg_ZI3qtABhg3kR7dsyyRO9CJbostm7tY0OasIT9HU8UmPo7Py-hm1x_nld-VMDXp3_qqW4k2UISVjOqLuFhc0MkN6VWDS_Nbvp1ik8joYIQ4QZsJnWJlg4WPHltxDuMnvTW2TACKHXDjBb5bd63JHEfGTWk_cVmQmYoy2fpJbN-ws-1rxkyc1XpMBYqgL9SituZ8wg1FAxPqEpGWwVhiY31nVhDFMt__1Zy9Wdk11JhvbkNFdoCpAItlzLfPa-8tMp_s6zinmzdj9SkgjNgHsXuwltaiC0bPG7RX0Cvyh5VfnJH-rNG9KPLo6RhxuKu81HYG1OB6otiunOVdosVXh0ZH0MKdY_Bw6AgkNWv8Yc85I16J56qCdvpC0bwFjshmEb_8k2jtPPFZfYZQh1hOIR_vdhmQmWL1_nkiIafYdLAJTxgIGPkRdwbjr-GThUp_zTTREutqJWyKFTz0ZRh_LNcWG2sSlz3VazeKJ_pkoLIPEJNN5MP7MZo2428T4ZzBuZDIssUMBHiXC4fMM3ky_c___1gLwd4'
]);

$topic = new \ChatSDK\Support\Topic();

//if qoustion id exist in table
//$topic->setTopic('grp/srv-1/45255077_5c1a63f832fca');
$topic->generateTopic(45255077);

$auth_subscription_link = DeepLinksChannel::generate_shortcut_subscription_link(
    $topic,
    '+',
    0,
    6,
    'https://www.jawabkom.com/front/access/login_by_phone',
    array(
        'category' => 'Medical',
        'expert_image' => 'https://myblue.bluecrossma.com/sites/g/files/csphws1086/files/inline-images/Doctor%20Image%20Desktop.png',
        'expert_name' => 'Dr. Sulaiman Abd Al-Hadi',
        'expert_title' => 'Licensed Doctor',
        'expert_subtitle' => 'Answered: 19,783 - Rating: 86.21%',
    )
);

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

echo "\n";

echo $auth_subscription_link;
echo "\n";

//echo $subscription_link;
//echo "\n";

//echo $chat_link;
//echo "\n";

//echo $login_link;
//echo "\n";