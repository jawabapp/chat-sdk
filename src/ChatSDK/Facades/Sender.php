<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/22/18
 * Time: 8:31 AM
 */

namespace ChatSDK\Facades;

use ChatSDK\Config\Repository;
use GuzzleHttp\Client as HttpClient;
use Exception;

class Sender extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sender';
    }

    public static function fetch($topic, $account_sender_id) {

        if(!Config::has('app_token')) {
            throw new Exception('The app token is required.');
        }

        try {

            $client = new HttpClient(['base_uri' => 'http://chat.jawab.app/api/']);

            $response = $client->request('POST', "service/sender/{$account_sender_id}", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Accept-Token' => Config::get('app_token'),
                ],
                'form_params' => [
                    'topic' => $topic
                ]
            ]);

            if($response->getStatusCode() != 200) {
                throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
            }

            $contents = json_decode($response->getBody()->getContents(), true);

            if(is_array($contents)) {
                static::swap(new Repository($contents));
            }

        } catch (Exception $e) {
            throw $e;
        }

    }
}
