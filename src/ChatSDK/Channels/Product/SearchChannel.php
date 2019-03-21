<?php
/**
 * SearchChannel
 *
 * @since     Mar 2019
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */
namespace ChatSDK\Channels\Product;

use ChatSDK\Facades\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;
use Exception;

class SearchChannel
{
    /**
     * @param $params
     * @return array
     * @throws RuntimeException
     * @throws Exception
     */
    public static function search($params)
    {

        if (!Config::has('products_endpoint')) {
            throw new RuntimeException('The topics endpoint is required.');
        }

        if (!Config::has('service_token')) {
            throw new RuntimeException('The service token is required.');
        }

        if (empty($params['language'])) {
            throw new RuntimeException('The language is required.');
        }

        try {
            $client = new Client();

            $queryParams = [];

            if (!empty($params['query'])) {
                $queryParams['query'] = $params['query'];
            }

            if (!empty($params['filter'])) {
                // Query params will overwrite the filters
                $queryParams = array_merge($params, $queryParams);
            }

            if (!empty($params['per_page'])) {
                $queryParams['per_page'] = $params['per_page'];
            }

            if (!empty($params['page'])) {
                $queryParams['page'] = $params['page'];
            }

            $response = $client->request('GET', Config::get('products_endpoint'), [
                'headers' => [
                    'Accept-Token' => Config::get('service_token'),
                    'Accept-Language' => $params['language'],
                ],
                'query' => $queryParams // the products_endpoint query parameters will disappear. Check the documentation http://docs.guzzlephp.org/en/stable/request-options.html#query
            ]);

            if((int)$response->getStatusCode() !== 200) {
                throw new RuntimeException('The remote endpoint could not be called, or the response it returned was invalid.');
            }

            $content = json_decode($response->getBody()->getContents(), true);

            if (!empty($content['items']) && is_array($content['items'])) {
                return array_map(function ($item) {

                    if (empty($item['id']) || empty($item['title']) ||
                        empty($item['price']) || empty($item['link'])) {
                        throw new RuntimeException('invalid products endpoint response');
                    }

                    return [
                        'id' => $item['id'],
                        'title' => $item['title'],
                        'price' => $item['price'],
                        'link' => $item['link'],
                        'image' => isset($item['image']) ? $item['image'] : '',
                        'discount_price' => isset($item['discount_price']) ? $item['discount_price'] : '',
                        'discount_rate' => isset($item['discount_rate']) ? $item['discount_rate'] : '',
                        'store_name' => isset($item['store_name']) ? $item['store_name'] : '',
                        'store_image' => isset($item['store_image']) ? $item['store_image'] : '',
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
