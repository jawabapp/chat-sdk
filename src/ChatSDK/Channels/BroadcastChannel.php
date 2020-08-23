<?php

namespace ChatSDK\Channels;

use ChatSDK\Facades\Config;
use ChatSDK\Support\HttpClient;
use GuzzleHttp\Client;
use Exception;

class BroadcastChannel
{
    public static function callback($params) {

        if(!Config::has('broadcast_callback_endpoint')) {
            throw new Exception('The broadcast callback endpoint is required.');
        }

        if(!Config::has('service_token')) {
            throw new Exception('The service token is required.');
        }

        if(empty($params['user_uuid'])) {
            throw new Exception('The user uuid is required.');
        }

        if(empty($params['user_phone'])) {
            throw new Exception('The user phone is required.');
        }

        $client = new Client();

        $response = $client->request('POST', Config::get('broadcast_callback_endpoint'), [
            'headers' => [
                'Accept-Token' => Config::get('service_token'),
            ],
            'form_params' => [
                'user_phone' => $params['user_phone'],
                'user_uuid' => $params['user_uuid'],
                'user_nickname' => $params['user_nickname'],
            ]
        ]);

        if($response->getStatusCode() != 200) {
            throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
        }

        return true;
    }

    private static function send(array $params) {

        if(!Config::has('app_token')) {
            throw new Exception('The app token is required.');
        }

        if(empty($params['content'])) {
            throw new Exception('The content is required.');
        }

        if(empty($params['content_type'])) {
            throw new Exception('The content_type is required.');
        }

        if(empty($params['user_uuid'])) {
            throw new Exception('The user uuid is required.');
        }

        try {

            $client = new HttpClient();

            $response = $client->request('POST', "sdk/broadcast-send", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Accept-Token' => Config::get('app_token'),
                ],
                'form_params' => $params
            ]);

            if ($response->getStatusCode() != 200) {
                throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
            }

            return json_decode($response->getBody()->getContents(), true);

        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function sendText($userUuid, $text) {

        if(empty($text)) {
            throw new Exception('The text is required.');
        }

        return self::send(array(
            'user_uuid' => $userUuid,
            'content_type' => 'text',
            'content' => $text,
        ));
    }

    public static function sendImage($userUuid, $imageUrl) {

        if(empty($imageUrl)) {
            throw new Exception('The image url is required.');
        }

        return self::send(array(
            'user_uuid' => $userUuid,
            'content_type' => 'image',
            'content' => $imageUrl
        ));
    }

    public static function sendLinkableImage($userUuid, $deepLink, $imageUrl, $imageHeight = null, $imageWidth = null) {

        if(empty($deepLink)) {
            throw new Exception('The deep link is required.');
        }

        if(empty($imageUrl)) {
            throw new Exception('The image url is required.');
        }

        if(function_exists('getimagesize') && (is_null($imageHeight) || is_null($imageWidth))) {
            list($imageWidth, $imageHeight) = getimagesize($imageUrl);
        }

        if(empty($imageWidth)) {
            throw new Exception('The image width is required.');
        }

        if(empty($imageHeight)) {
            throw new Exception('The image height is required.');
        }

        return self::send(array(
            'user_uuid' => $userUuid,
            'content_type' => 'image_linkable',
            'content' => json_encode(array(
                'deep_link' => $deepLink,
                'image' => $imageUrl,
                'height' => $imageHeight,
                'width' => $imageWidth,
            ))
        ));
    }

    public static function sendForceDeepLink($userUuid, $deepLink, $notificationTitle) {

        if(empty($deepLink)) {
            throw new Exception('The deep link is required.');
        }

        if(empty($notificationTitle)) {
            throw new Exception('The notification title is required.');
        }

        return self::send(array(
            'user_uuid' => $userUuid,
            'content_type' => 'force_subscription',
            'content' => json_encode(array(
                'deep_link' => $deepLink,
                'notification_title' => $notificationTitle
            ))
        ));
    }

    public static function sendPremium($userUuid, $deepLink, $notificationTitle, $title, $subTitle, $buttonTitle,array $details, $language = 'en',array $colors = array(),array $bolds = array()) {

        if(empty($deepLink)) {
            throw new Exception('The deep link is required.');
        }

        if(empty($title)) {
            throw new Exception('The title is required.');
        }

        if(empty($subTitle)) {
            throw new Exception('The sub title is required.');
        }

        if(empty($buttonTitle)) {
            throw new Exception('The button title is required.');
        }

        if(empty($details)) {
            throw new Exception('The details is required.');
        }

        if(empty($notificationTitle)) {
            throw new Exception('The notification title is required.');
        }

        return self::send(array(
            'user_uuid' => $userUuid,
            'content_type' => 'premium',
            'content' => json_encode(array(
                'notification_title' => $notificationTitle,

                //fonts
                'bold_title' => empty($bolds['title']) ? false : $bolds['title'],
                'bold_subtitle' => empty($bolds['subtitle']) ? false : $bolds['subtitle'],
                'bold_details' => empty($bolds['details']) ? false : $bolds['details'],
                'bold_button' => empty($bolds['button']) ? false : $bolds['button'],

                //colors
                'color_text_title' => empty($colors['text_title']) ? "#000000" : $colors['text_title'],
                'color_text_subtitle' => empty($colors['text_subtitle']) ? "#ffffff" : $colors['text_subtitle'],
                'color_text_details' => empty($colors['text_details']) ? "#ffffff" : $colors['text_details'],
                'color_text_button' => empty($colors['text_button']) ? "#ffffff" : $colors['text_button'],
                'color_background' => empty($colors['background']) ? "#4fa9e1" : $colors['background'],
                'color_button' => empty($colors['button']) ? "#3b6ba8" : $colors['button'],

                'language' => $language,
                'deep_link' => $deepLink,
                'title' => $title,
                'sub_title' => $subTitle,
                'button_title' => $buttonTitle,
                'details' => $details,
            ))
        ));
    }

    public static function sendSubscription($userUuid, $deepLink, $notificationTitle, $description, $buttonTitle, array $expert = array(), $language = 'en',array $colors = array(),array $bolds = array()) {

        if(empty($deepLink)) {
            throw new Exception('The deep link is required.');
        }

        if(empty($description)) {
            throw new Exception('The description is required.');
        }

        if(empty($buttonTitle)) {
            throw new Exception('The button title is required.');
        }

        if(empty($notificationTitle)) {
            throw new Exception('The notification title is required.');
        }

        return self::send(array(
            'user_uuid' => $userUuid,
            'content_type' => 'subscription',
            'content' => json_encode(array(
                'notification_title' => $notificationTitle,

                //fonts
                'bold_expert' => empty($bolds['expert']) ? false : $bolds['expert'],
                'bold_description' => empty($bolds['description']) ? false : $bolds['description'],
                'bold_button' => empty($bolds['button']) ? false : $bolds['button'],

                //colors
                'color_text_expert' => empty($colors['text_expert']) ? "#000000" : $colors['text_expert'],
                'color_text_description' => empty($colors['text_description']) ? "#000000" : $colors['text_description'],
                'color_text_button' => empty($colors['text_button']) ? "#ffffff" : $colors['text_button'],
                'color_background' => empty($colors['background']) ? "#ffffff" : $colors['background'],
                'color_button' => empty($colors['button']) ? "#24db27" : $colors['button'],

                'language' => $language,
                'expert_enabled' => empty($expert['name']) ? false : true,
                'expert_image' => empty($expert['image']) ? "" : $expert['image'],
                'expert_name' => empty($expert['name']) ? "" : $expert['name'],
                'expert_title' => empty($expert['title']) ? "" : $expert['title'],
                'expert_subtitle' => empty($expert['subtitle']) ? "" : $expert['subtitle'],
                'deep_link' => $deepLink,
                'button_title' => $buttonTitle,
                'description' => $description,
            ))
        ));
    }

}
