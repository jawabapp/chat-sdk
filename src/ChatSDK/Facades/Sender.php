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

class Sender extends Facade
{
    private static $cache = array();

    protected static function getFacadeAccessor()
    {
        return 'sender';
    }

    public static function fetch($phone, $topic, $name = null, $avatar = null) {

        if(!Config::has('app_token')) {
            throw new Exception('The app token is required.');
        }

        try {

            if(!empty(self::$cache[$topic][$phone])) {
                $contents = self::$cache[$topic][$phone];
            } else {
                $client = new HttpClient();

                $response = $client->request('POST', "sdk/sender", [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Accept-Token' => Config::get('app_token'),
                    ],
                    'form_params' => [
                        'topic' => $topic,
                        'phone' => $phone,
                        'name' => $name,
                        'avatar' => $avatar,
                    ]
                ]);

                if($response->getStatusCode() != 200) {
                    throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
                }

                $contents = json_decode($response->getBody()->getContents(), true);

                if(!is_array($contents)) {
                    $contents = array();
                }

                self::$cache[$topic][$phone] = $contents;
            }

            static::swap(new Repository($contents));

        } catch (Exception $e) {
            throw $e;
        }

    }

    public static function clearCache() {
        self::$cache = array();
    }
}
