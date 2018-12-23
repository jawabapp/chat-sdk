<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/14/18
 * Time: 12:14 AM
 */

namespace ChatSDK\Channels;

use ChatSDK\Facades\Client;
use ChatSDK\Facades\Config;
use ChatSDK\Support\HttpClient;
use ChatSDK\Support\Topic;
use Exception;

class DeepLinksChannel
{
    /**
     * @param $topic
     * @param $phone
     * @param $packageId
     * @param $link
     * @param string $socialTitle
     * @param string $socialDescription
     * @param string $socialImageLink
     * @return mixed
     * @throws Exception | \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_subscription_link(Topic $topic, $phone, $packageId, $link, $socialTitle = '', $socialDescription = '', $socialImageLink = '') {

        Client::make();

        $link = self::handle_url($link, [
            'service_id' => Client::get('id'),
            'mode' => 'subscription',
            'topic' => $topic->getTopic(),
            'phone' => $phone,
            'package_id' => $packageId,
        ]);

        return self::generate($link, $socialTitle, $socialDescription, $socialImageLink);
    }

    /**
     * @param Topic $topic
     * @param $phone
     * @param $link
     * @param string $socialTitle
     * @param string $socialDescription
     * @param string $socialImageLink
     * @return mixed
     * @throws Exception | \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_chat_link(Topic $topic, $phone, $link, $socialTitle = '', $socialDescription = '', $socialImageLink = '') {

        Client::make();

        $link = self::handle_url($link, [
            'service_id' => Client::get('id'),
            'mode' => 'chat',
            'topic' => $topic->getTopic(),
            'phone' => $phone,
        ]);

        return self::generate($link, $socialTitle, $socialDescription, $socialImageLink);
    }

    /**
     * @param $phone
     * @param $link
     * @param string $socialTitle
     * @param string $socialDescription
     * @param string $socialImageLink
     * @return mixed
     * @throws Exception | \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_login_link($phone, $link, $socialTitle = '', $socialDescription = '', $socialImageLink = '') {

        Client::make();

        $link = self::handle_url($link, [
            'service_id' => Client::get('id'),
            'mode' => 'login',
            'phone' => $phone
        ]);

        return self::generate($link, $socialTitle, $socialDescription, $socialImageLink);

    }

    /**
     * @param $link
     * @param string $socialTitle
     * @param string $socialDescription
     * @param string $socialImageLink
     * @return mixed
     * @throws Exception | \GuzzleHttp\Exception\GuzzleException
     */
    private static function generate($link, $socialTitle = '', $socialDescription = '', $socialImageLink = '') {

        $client = new HttpClient();

        $response = $client->request('POST', 'service/sdk/deep-link', [
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Token' => Config::get('app_token'),
            ],
            'form_params' => [
                'link' => $link,
                'socialTitle' => $socialTitle,
                'socialDescription' => $socialDescription,
                'socialImageLink' => $socialImageLink
            ]
        ]);

        if($response->getStatusCode() != 200) {
            throw new Exception('The remote endpoint could not be called, or the response it returned was invalid.');
        }

        try {
            $content = json_decode($response->getBody()->getContents(), true);

            if(!empty($content['shortLink'])) {
                return $content['shortLink'];
            }

            throw new Exception('invalid short Link');

        } catch (Exception $e) {
            throw $e;
        }

    }

    /**
     * @param $request_url
     * @param array $queries
     * @return string
     */
    private static function handle_url($request_url, $queries = array()) {

        if(!is_string($request_url)) {
            return '';
        }

        $url = explode('?', $request_url);
        $matches = array();
        preg_match("/^[?].*/", $request_url, $matches);

        $out_url = '';
        if (empty($matches)) {
            $out_url .= $url[0];
        }

        $query_string_array = array();
        if (isset($url[1]) AND $url[1]) {
            parse_str($url[1], $query_string_array);
        }

        if($queries) {
            foreach($queries as $label => $value) {
                $query_string_array[$label] = $value;
            }
        }

        $out_url .= '?' . http_build_query($query_string_array);

        return $out_url;
    }
}

