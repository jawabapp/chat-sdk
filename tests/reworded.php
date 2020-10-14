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

echo \ChatSDK\Channels\DeepLinksChannel::generate_reworded_ads_link(
    'https://playwin.app/en/login',
    'https://playwin.app/en/login-by-phone',
    'https://playwin.app/en/login',
    'test-web-uuid-test',
    [
        'logo' => 'https://playwin.app/images/logo/logo_1586108912logo.png',
        'title' => 'لقد إستنفذت محاولاتك',
        'subtitle' => 'إلعب جولة اخرى مجاناً',
        'image' => 'https://s3.jawab.app/chat/group-1655.png',
        'button_text' => 'إلعب جولة اخرى',
        'button_icon' => 'https://s3.jawab.app/chat/group-1654.png',
        'button_text_color' => '#404040',
        'button_color' => '#FFDA04',
        'gradient_start_color' => '#0095FF',
        'gradient_end_color' => '#00AD6C',
    ]
);

echo "\n";
