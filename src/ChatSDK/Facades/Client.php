<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/21/18
 * Time: 8:09 PM
 */

namespace ChatSDK\Facades;

use ChatSDK\Config\Repository;
use ChatSDK\Support\HttpClient;
use Exception;

class Client extends Facade
{

    private static $data = array();

    protected static function getFacadeAccessor()
    {
        return 'client';
    }

    public static function make() {

        if(!Config::has('app_token')) {
            throw new Exception('The app token is required.');
        }

        if(empty(self::$data)) {

            try {

                $client = new HttpClient();

                $response = $client->request('GET', 'service/sdk/info', [
                    'headers' => [
                        'Accept-Token' => Config::get('app_token'),
                    ]
                ]);

                if($response->getStatusCode() != 200) {
                    throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
                }

                $contents = json_decode($response->getBody()->getContents(), true);

                if(!is_array($contents)) {
                    $contents = array();
                }

                $contents['host'] = Config::get('host');
                $contents['port'] = 1883;

                if(isset($contents['id'])) {
                    $contents['client_id'] = uniqid("service_{$contents['id']}_");
                    $contents['receive_client_id'] = "service_{$contents['id']}_receive";
                    $contents['topic_prefix'] = "grp/srv-{$contents['id']}";
                }

                self::$data = $contents;

            } catch (Exception $e) {
                throw $e;
            }

        }

        static::swap(new Repository(self::$data));

    }
}
