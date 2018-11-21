<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/21/18
 * Time: 8:09 PM
 */

namespace ChatSDK\Facades;

use ChatSDK\Config\Repository;
use GuzzleHttp\Client as HttpClient;
use Exception;

class Client extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'client';
    }

    public static function make() {

        if(!Config::has('app_token')) {
            throw new Exception('The app token is required.');
        }

        $client = new HttpClient(['base_uri' => 'http://chat.jawab.app/api/']);

        $response = $client->request('GET', 'service/info', [
            'headers' => [
                'Accept-Token' => Config::get('app_token'),
            ]
        ]);

        if($response->getStatusCode() != 200) {
            throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
        }

        try {

            $content = json_decode($response->getBody()->getContents(), true);

            $repository = new Repository($content);

            static::swap($repository);

            return $repository;

        } catch (Exception $e) {
            throw $e;
        }

    }
}
