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

class DeleteUserChannel
{
    public static function delete($params) {

        if(!Config::has('delete_endpoint')) {
            throw new Exception('The delete endpoint is required.');
        }

        if(!Config::has('service_token')) {
            throw new Exception('The service token is required.');
        }

        if(empty($params['user_phone'])) {
            throw new Exception('The transaction id is required.');
        }

        $client = new Client();

        $response = $client->request('POST', Config::get('delete_endpoint'), [
            'headers' => [
                'Accept-Token' => Config::get('service_token'),
            ],
            'form_params' => [
                'user_phone' => $params['user_phone']
            ]
        ]);

        if($response->getStatusCode() != 200) {
            throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
        }

        return true;
    }

}
