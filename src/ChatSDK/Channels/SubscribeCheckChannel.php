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

class SubscribeCheckChannel
{
    public static function create($params) {

        if(!Config::has('user_endpoint')) {
            throw new Exception('The user endpoint is required.');
        }

        if(!Config::has('service_token')) {
            throw new Exception('The service token is required.');
        }

        if(empty($params['topic'])) {
            throw new Exception('The topic is required.');
        }

        $client = new Client();

        $response = $client->request('POST', Config::get('subscribe_check_endpoint'), [
            'headers' => [
                'Accept-Token' => Config::get('service_token'),
            ],
            'form_params' => [
                'topic' => $params['topic'],
            ]
        ]);

        if($response->getStatusCode() != 200) {
            throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
        }

        try {
            $content = json_decode($response->getBody()->getContents(), true);

            if(!empty($content['user_id']) && !empty($content['user_name'])) {
                return [
                    'user_id' => $content['user_id'],
                    'user_name' => $content['user_name'],
                    'user_phone' => isset($content['user_phone']) ? $content['user_phone'] : '',
                    'user_avatar' => isset($content['user_avatar']) ? $content['user_avatar'] : '',
                ];
            }

            throw new Exception('invalid user endpoint response');

        } catch (Exception $e) {
            throw $e;
        }
    }
}
