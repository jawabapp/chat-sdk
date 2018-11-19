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

class UserChannel
{
    public static function create($params) {

        if(!Config::has('user_endpoint')) {
            throw new Exception('The user endpoint is required.');
        }

        if(!Config::has('service_token')) {
            throw new Exception('The service token is required.');
        }

        if(empty($params['category_id'])) {
            throw new Exception('The category id is required.');
        }

        if(empty($params['language'])) {
            throw new Exception('The language is required.');
        }

        $client = new Client();

        $response = $client->request('POST', Config::get('user_endpoint'), [
            'headers' => [
                'Accept-Token' => Config::get('service_token'),
                'Accept-Language' => $params['language']
            ],
            'form_params' => [
                'category_id' => $params['category_id']
            ]
        ]);

        if($response->getStatusCode() != 200) {
            throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
        }

        $content = json_decode($response->getBody()->getContents());

        return [
            'ref_id' => $content->ref_id ,
            'ref_name' => $content->ref_name,
            'ref_avatar' => $content->ref_avatar,
        ];
    }
}