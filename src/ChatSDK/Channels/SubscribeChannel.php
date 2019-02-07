<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/14/18
 * Time: 10:57 AM
 */

namespace ChatSDK\Channels;

use ChatSDK\Facades\Config;
use GuzzleHttp\Client;
use Exception;

class SubscribeChannel
{
    public static function create($params) {

        if(!Config::has('user_endpoint')) {
            throw new Exception('The user endpoint is required.');
        }

        if(!Config::has('service_token')) {
            throw new Exception('The service token is required.');
        }

        if(empty($params['transaction_id'])) {
            throw new Exception('The transaction id is required.');
        }

        $client = new Client();

        $response = $client->request('POST', Config::get('subscribe_endpoint'), [
            'headers' => [
                'Accept-Token' => Config::get('service_token'),
            ],
            'form_params' => [
                'user_phone' => $params['user_phone'],
                'user_nickname' => $params['user_nickname'],
                'user_topics' => $params['topics'],
                'os' => $params['os'],
                'device_info' => $params['device_info'],
                'purchased_at' => $params['purchased_at'],
                'expired_at' => $params['expired_at'],
                'product_id' => $params['product_id'],
                'transaction_id' => $params['transaction_id'],
                'price_currency' => $params['price_currency'],
                'price_amount' => $params['price_amount'],
                'notification_type' => $params['notification_type'],
            ]
        ]);

        if($response->getStatusCode() != 200) {
            throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
        }

        return true;
    }

    public static function check($params) {

        if(!Config::has('user_endpoint')) {
            throw new Exception('The user endpoint is required.');
        }

        if(!Config::has('service_token')) {
            throw new Exception('The service token is required.');
        }

        if(empty($params['user_phone'])) {
            throw new Exception('The phone is required.');
        }

        $client = new Client();

        $response = $client->request('POST', Config::get('subscribe_check_endpoint'), [
            'headers' => [
                'Accept-Token' => Config::get('service_token'),
            ],
            'form_params' => [
                'user_phone' => $params['user_phone'],
            ]
        ]);

        if($response->getStatusCode() != 200) {
            throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
        }

        try {

            $content = json_decode($response->getBody()->getContents(), true);

            return isset($content['is_subscribe']) ?  boolval($content['is_subscribe']) : false;

        } catch (Exception $e) {
            throw $e;
        }
    }

}
