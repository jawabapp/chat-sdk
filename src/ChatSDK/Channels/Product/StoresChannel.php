<?php
/**
 * Created by PhpStorm.
 * User: Ibraheem Qanah
 * Date: 2019-03-29
 * Time: 12:11
 */

namespace ChatSDK\Channels\Product;

use ChatSDK\Facades\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;
use Exception;

class StoresChannel
{
    /**
     * @param $params
     * @return array
     * @throws RuntimeException
     * @throws Exception
     */
    public static function fetch($user_info)
    {
        if (!Config::has('stores_endpoint')) {
            throw new RuntimeException('The stores endpoint is required.');
        }

        if (!Config::has('service_token')) {
            throw new RuntimeException('The service token is required.');
        }

        if (empty($user_info['user_name'])) {
            throw new RuntimeException('The user name is required.');
        }

        if (empty($user_info['user_phone'])) {
            throw new RuntimeException('The user phone is required.');
        }

        if (empty($user_info['user_country'])) {
            throw new RuntimeException('The user country is required.');
        }

        if (empty($user_info['user_device_os'])) {
            throw new RuntimeException('The user device os is required.');
        }

        if (empty($user_info['user_language'])) {
            throw new RuntimeException('The user language is required.');
        }

        try {
            $client = new Client();

            $response = $client->request('GET', Config::get('stores_endpoint'), [
                'headers' => [
                    'Accept-Token' => Config::get('service_token'),
                    'Accept-Language' => $user_info['user_language'],
                    'Accept-User' => base64_encode(serialize($user_info)),
                ]
            ]);

            if((int)$response->getStatusCode() !== 200) {
                throw new RuntimeException('The remote endpoint could not be called, or the response it returned was invalid.');
            }

            $content = json_decode($response->getBody()->getContents(), true);

            if (!empty($content['items']) && is_array($content['items'])) {
                return array_map(function ($item) {

                    if (empty($item['id']) || empty($item['title'])) {
                        throw new RuntimeException('invalid stores endpoint response');
                    }

                    return [
                        'id' => $item['id'],
                        'title' => $item['title'],
                        'image' => isset($item['image']) ? $item['image'] : '',
                        'description' => isset($item['description']) ? $item['description'] : '',
                    ];
                }, $content['items']);
            }

            // we will return empty array if the search result dont find any product instead of throwing an exception
            return [];

        } catch (GuzzleException $e) {
            throw new RuntimeException('Service provider is not reachable', $e->getCode(), $e);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
