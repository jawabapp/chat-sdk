<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/14/18
 * Time: 10:57 AM
 */

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

    public static function sendForceDeepLink($userUuid, $deepLink) {

        if(empty($deepLink)) {
            throw new Exception('The deep link is required.');
        }

        return self::send(array(
            'user_uuid' => $userUuid,
            'content_type' => 'force_subscription',
            'content' => json_encode(array(
                'deep_link' => $deepLink
            ))
        ));
    }

}
