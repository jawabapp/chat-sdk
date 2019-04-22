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

    public static $headerPrefix = 'Custom-';

    /**
     * @param $params
     * @param $user_info
     * @param array $headers
     * @return array
     * @throws Exception
     */
    public static function search($params, $user_info, $headers = array())
    {
        if (!Config::has('products_endpoint')) {
            throw new RuntimeException('The products endpoint is required.');
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

            $queryParams = [];

            if (!empty($params['query'])) {
                $queryParams['query'] = $params['query'];
            }

            if (!empty($params['filter'])) {
                // Query params will overwrite the filters
                $queryParams = array_merge($params['filter'], $queryParams);
            }

            if (!empty($params['per_page'])) {
                $queryParams['per_page'] = $params['per_page'];
            }

            if (!empty($params['page'])) {
                $queryParams['page'] = $params['page'];
            }

            $headers = array_filter($headers, function ($headerValue, $headerKey) {
                return strpos($headerKey, self::$headerPrefix) === 0;
            }, ARRAY_FILTER_USE_BOTH);

            $response = $client->request('GET', Config::get('products_endpoint'), [
                'headers' => array_merge($headers, [
                    'Accept-Token' => Config::get('service_token'),
                    'Accept-Language' => $user_info['user_language'],
                    'Accept-User' => base64_encode(serialize($user_info)),
                ]),
                'query' => $queryParams // the products_endpoint query parameters will disappear. Check the documentation http://docs.guzzlephp.org/en/stable/request-options.html#query
            ]);

            if((int)$response->getStatusCode() !== 200) {
                throw new RuntimeException('The remote endpoint could not be called, or the response it returned was invalid.');
            }

            $content = json_decode($response->getBody()->getContents(), true);

            $headerKeys = array_filter(array_keys($response->getHeaders()), function ($header) {
                return strpos($header, self::$headerPrefix) === 0;
            });

            $responseHeaders = [];
            if($headerKeys) {
                foreach ($headerKeys as $headerKey) {
                    $header = ucwords($headerKey, '-');
                    $responseHeaders[$header] = $response->getHeaderLine($header);
                }
            }

            $items = array_map(function ($item) {

                if (empty($item['id']) || empty($item['title'])) {
                    throw new RuntimeException('invalid products endpoint response');
                }

                return [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'price' => isset($item['price']) ? $item['price'] : '',
                    'link' => isset($item['link']) ? $item['link'] : '',
                    'share_link' => isset($item['share_link']) ? $item['share_link'] : '',
                    'image' => isset($item['image']) ? $item['image'] : '',
                    'discount_price' => isset($item['discount_price']) ? $item['discount_price'] : '',
                    'discount_rate' => isset($item['discount_rate']) ? $item['discount_rate'] : '',
                    'is_free_shipping' => isset($item['is_free_shipping']) ? $item['is_free_shipping'] : false,
                    'condition' => isset($item['condition']) ? $item['condition'] : '',
                    'store_name' => isset($item['store_name']) ? $item['store_name'] : '',
                    'store_image' => isset($item['store_image']) ? $item['store_image'] : '',
                ];
            }, (isset($content['items']) && is_array($content['items']) ? $content['items'] : array()));

            $pagination = [
                "total" => isset($content['pagination']['total']) ? $content['pagination']['total'] : 0,
                "per_page" => isset($content['pagination']['per_page']) ? $content['pagination']['per_page'] : 0,
                "current_page" => isset($content['pagination']['current_page']) ? $content['pagination']['current_page'] : 0,
                "last_page" => isset($content['pagination']['last_page']) ? $content['pagination']['last_page'] : 0,
            ];

            return [
                $items,
                $pagination,
                $responseHeaders
            ];

        } catch (GuzzleException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
