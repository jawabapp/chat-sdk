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

class TopicsChannel
{
    public static function fetch($params) {

        if(!Config::has('topics_endpoint')) {
            throw new Exception('The topics endpoint is required.');
        }

        if(!Config::has('service_token')) {
            throw new Exception('The service token is required.');
        }

        if(empty($params['language'])) {
            throw new Exception('The language is required.');
        }

        $client = new Client();

        $response = $client->request('GET', Config::get('topics_endpoint'), [
            'headers' => [
                'Accept-Token' => Config::get('service_token'),
                'Accept-Language' => $params['language']
            ]
        ]);

        if($response->getStatusCode() != 200) {
            throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
        }

        try {
            $content = json_decode($response->getBody()->getContents());

            $key = 0;
            $items = [];

            foreach ($content->items as $item) {
                $items[$key]['id'] = $item->id;
                $items[$key]['title'] = $item->title;

                $key++;
            }

            return $items;
        } catch (Exception $e) {
            throw $e;
        }
    }
}