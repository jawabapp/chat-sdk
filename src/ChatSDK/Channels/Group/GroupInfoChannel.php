<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/14/18
 * Time: 10:57 AM
 */

namespace ChatSDK\Channels\Group;

use ChatSDK\Facades\Config;
use ChatSDK\Support\HttpClient;
use Exception;

class GroupInfoChannel
{
    public static function update(array $params) {

        if(!Config::has('app_token')) {
            throw new Exception('The app token is required.');
        }

        if(empty($params['topic'])) {
            throw new Exception('The topic is required.');
        }

        if(empty($params['name'])) {
            throw new Exception('The name is required.');
        }

        try {

            $client = new HttpClient();

            $response = $client->request('POST', "sdk/receiver", [
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
}
