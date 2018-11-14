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

class GroupChannel
{
    public static function create($params) {

        $client = new Client();

        $response = $client->request('POST', Config::get('service_endpoint'), [
            'headers' => [
                'Authorization' => Config::get('service_token')
            ],
            'form_params' => [
                'category_id' => isset($params['category_id']) ? $params['category_id'] : null
            ]
        ]);

        if($response->getStatusCode() != 200) {
            throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
        }

        $responseContent = $response->getBody()->getContents();

        #TODO jawabkom expert info (id, name, avatar)

        #TODO Post the data to the service_endpoint to return sender info

        #TODO Post sender to jawabChat to Create jawabChatUser

        #TODO Create mqtt_topic (grp/app-{id}/{creatorAccountId}_{now})

        return [
            'ref_id' => 1,
            'ref_name' => 'Ibraheem qanah',
            'ref_avatar' => "http://",
        ];
    }
}