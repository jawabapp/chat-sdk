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
        'image' => 'https://img.pngio.com/3d-red-heart-png-image-png-mart-3d-heart-png-1200_1200.png',
        'button_text' => 'إلعب جولة اخرى',
        'button_color' => '#ffda00',
        'gradient_start_color' => '#0095fd',
        'gradient_end_color' => '#14b077',
    ]
);

echo "\n";
