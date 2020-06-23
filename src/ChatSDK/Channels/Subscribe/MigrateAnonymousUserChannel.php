<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/14/18
 * Time: 10:57 AM
 */

namespace ChatSDK\Channels\Subscribe;

use ChatSDK\Facades\Config;
use GuzzleHttp\Client;
use Exception;

class MigrateAnonymousUserChannel
{
    public static function migrate($params) {

        if(!Config::has('migrate_user_endpoint')) {
            throw new Exception('The migrate user endpoint is required.');
        }

        if(!Config::has('service_token')) {
            throw new Exception('The service token is required.');
        }

        if(empty($params['anonymous_uuid'])) {
            throw new Exception('The anonymous uuid is required.');
        }

        if(empty($params['user_uuid'])) {
            throw new Exception('The user uuid is required.');
        }

        if(empty($params['user_phone'])) {
            throw new Exception('The user phone is required.');
        }

        $client = new Client();

        $response = $client->request('POST', Config::get('migrate_user_endpoint'), [
            'headers' => [
                'Accept-Token' => Config::get('service_token'),
            ],
            'form_params' => [
                //anonymous
                'anonymous_uuid' => $params['anonymous_uuid'],
                'anonymous_topics' => $params['anonymous_topics'],
                //real
                'user_phone' => $params['user_phone'],
                'user_uuid' => $params['user_uuid'],
                'user_nickname' => $params['user_nickname'],
                'user_topics' => $params['user_topics']
            ]
        ]);

        if($response->getStatusCode() != 200) {
            throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
        }

        return true;
    }

}
