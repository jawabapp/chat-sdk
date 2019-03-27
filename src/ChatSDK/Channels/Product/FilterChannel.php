<?php
/**
 * Created by PhpStorm.
 * User: ibraheemqanah
 * Date: 2019-03-24
 * Time: 12:11
 */

namespace ChatSDK\Channels\Product;


use ChatSDK\Facades\Config;
use ChatSDK\Support\HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

class FilterChannel
{
    /**
     * @param array $filters
     * @return mixed
     * @throws Exception
     */
    public static function build(array $filters) {

        if(!Config::has('app_token')) {
            throw new Exception('The app token is required.');
        }

        try {

            $client = new HttpClient();

            $response = $client->request('POST', 'sdk/product/filter-builder', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Accept-Token' => Config::get('app_token'),
                ],
                'form_params' => [
                    'filters' => $filters
                ]
            ]);

            if($response->getStatusCode() != 200) {
                throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
            }

            $content = json_decode($response->getBody()->getContents(), true);

            if(!empty($content['remote_config_name'])) {
                return $content['remote_config_name'];
            }

            throw new Exception('invalid remote config name');

        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }
    }
}