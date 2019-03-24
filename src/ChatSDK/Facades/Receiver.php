<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/22/18
 * Time: 8:31 AM
 */

namespace ChatSDK\Facades;

use ChatSDK\Config\Repository;
use ChatSDK\Support\HttpClient;
use Exception;

class Receiver extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'receiver';
    }

    public static function fetch($topic, $account_sender_id) {

        if(!Config::has('app_token')) {
            throw new Exception('The app token is required.');
        }

        try {

            $client = new HttpClient();

            $response = $client->request('POST', "sdk/receiver", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Accept-Token' => Config::get('app_token'),
                ],
                'form_params' => [
                    'topic' => $topic,
                    'account_sender_id' => $account_sender_id,
                ]
            ]);

            if($response->getStatusCode() != 200) {
                throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
            }

            $contents = json_decode($response->getBody()->getContents(), true);

            if(!is_array($contents)) {
                $contents = array();
            }

            static::swap(new Repository($contents));

        } catch (Exception $e) {
            throw $e;
        }

    }
}
