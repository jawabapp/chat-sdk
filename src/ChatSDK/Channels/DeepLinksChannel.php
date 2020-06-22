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
     * @param $packageId
     * @param $desktopLink
     * @param $redirectUri
     * @param $webUuid
     * @param array $placeholders
     * @param array $analyticsInfo
     * @param array $socialInfo
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_webview_link($packageId, $desktopLink, $redirectUri, $webUuid = null, $placeholders = array(), $analyticsInfo = array(), $socialInfo = array()) {

        Client::make();

        $link = self::handle_url($desktopLink, self::handle_placeholders($placeholders, array(
            'service_id' => Client::get('id'),
            'mode' => 'webview',
            'redirect_uri' => urlencode($redirectUri),
            'package_id' => $packageId,
            'web_uuid' => $webUuid,
        )));

        return self::generate($link, $analyticsInfo, $socialInfo);
    }

    /**
     * @param $packageId
     * @param $desktopLink
     * @param $successRedirectUri
     * @param $failedRedirectUri
     * @param $webUuid
     * @param array $placeholders
     * @param array $analyticsInfo
     * @param array $socialInfo
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_webview_subscription_link($packageId, $desktopLink, $successRedirectUri, $failedRedirectUri, $webUuid = null, $placeholders = array(), $analyticsInfo = array(), $socialInfo = array()) {

        Client::make();

        $link = self::handle_url($desktopLink, self::handle_placeholders($placeholders, array(
            'service_id' => Client::get('id'),
            'mode' => 'webview-subscription',
            'success_redirect_uri' => urlencode($successRedirectUri),
            'failed_redirect_uri' => urlencode($failedRedirectUri),
            'package_id' => $packageId,
            'web_uuid' => $webUuid,
        )));

        return self::generate($link, $analyticsInfo, $socialInfo);
    }

    /**
     * @param Topic $topic
     * @param $phone
     * @param $packageId
     * @param $link
     * @param array $placeholders
     * @param array $analyticsInfo
     * @param array $socialInfo
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_premium_subscription_link(Topic $topic, $phone, $packageId, $link, $placeholders = array(), $analyticsInfo = array(), $socialInfo = array()) {

        Client::make();

        $link = self::handle_url($link, self::handle_placeholders($placeholders, array(
            'service_id' => Client::get('id'),
            'mode' => 'subscription-premium',
            'topic' => $topic->getTopic(),
            'phone' => $phone,
            'package_id' => $packageId,
        )));

        return self::generate($link, $analyticsInfo, $socialInfo);
    }

    /**
     * @param Topic $topic
     * @param $phone
     * @param $isSubscribed
     * @param $packageId
     * @param $link
     * @param array $placeholders
     * @param array $analyticsInfo
     * @param array $socialInfo
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_shortcut_subscription_link(Topic $topic, $phone, $isSubscribed, $packageId, $link, $placeholders = array(), $analyticsInfo = array(), $socialInfo = array()) {

        Client::make();

        $link = self::handle_url($link, self::handle_placeholders($placeholders, array(
            'service_id' => Client::get('id'),
            'mode' => 'subscription-shortcut',
            'topic' => $topic->getTopic(),
            'phone' => $phone,
            'is_subscribed' => $isSubscribed,
            'package_id' => $packageId,
        )));

        return self::generate($link, $analyticsInfo, $socialInfo);
    }

    /**
     * @param Topic $topic
     * @param $phone
     * @param $packageId
     * @param $link
     * @param array $placeholders
     * @param array $analyticsInfo
     * @param array $socialInfo
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_subscription_link(Topic $topic, $phone, $packageId, $link, $placeholders = array(), $analyticsInfo = array(), $socialInfo = array()) {

        Client::make();

        $link = self::handle_url($link, self::handle_placeholders($placeholders, array(
            'service_id' => Client::get('id'),
            'mode' => 'subscription',
            'topic' => $topic->getTopic(),
            'phone' => $phone,
            'package_id' => $packageId,
        )));

        return self::generate($link, $analyticsInfo, $socialInfo);
    }

    /**
     * @param Topic $topic
     * @param $phone
     * @param $link
     * @param array $placeholders
     * @param array $analyticsInfo
     * @param array $socialInfo
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_chat_link(Topic $topic, $phone, $link, $placeholders = array(), $analyticsInfo = array(), $socialInfo = array()) {

        Client::make();

        $link = self::handle_url($link, self::handle_placeholders($placeholders, array(
            'service_id' => Client::get('id'),
            'mode' => 'chat',
            'topic' => $topic->getTopic(),
            'phone' => $phone,
        )));

        return self::generate($link, $analyticsInfo, $socialInfo);
    }

    /**
     * @param $phone
     * @param $link
     * @param array $placeholders
     * @param array $analyticsInfo
     * @param array $socialInfo
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_login_link($phone, $link, $placeholders = array(), $analyticsInfo = array(), $socialInfo = array()) {

        Client::make();

        $link = self::handle_url($link, self::handle_placeholders($placeholders, array(
            'service_id' => Client::get('id'),
            'mode' => 'login',
            'phone' => $phone
        )));

        return self::generate($link, $analyticsInfo, $socialInfo);

    }

    /**
     * @param string $hashTag
     * @param array $placeholders
     * @param array $analyticsInfo
     * @param array $socialInfo
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_tag_link($hashTag, $placeholders = array(), $analyticsInfo = array(), $socialInfo = array()) {

        Client::make();

        $uriPrefix = 'https://hashtag.jawab.app';

        $hashTag = (str_replace('#', '', $hashTag));

        $link = "https://trends.jawab.app/hashtag/{$hashTag}";

        $link = self::handle_url("$link", self::handle_placeholders($placeholders, array(
            'mode' => 'hashtag',
            'hashtag' => $hashTag
        )));

        return self::generate($link, $analyticsInfo, $socialInfo, $uriPrefix);
    }

    /**
     * @param $link
     * @param array $analyticsInfo
     * @param array $socialInfo
     * @param string $uriPrefix
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private static function generate($link, $analyticsInfo = array(), $socialInfo = array(), $uriPrefix = null) {

        $client = new HttpClient();

        $response = $client->request('POST', 'sdk/deep-link', [
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Token' => Config::get('app_token'),
            ],
            'form_params' => [
                'link' => $link,
                'uriPrefix' => $uriPrefix,
                'socialTitle' => isset($socialInfo['title']) ? $socialInfo['title'] : '',
                'socialDescription' => isset($socialInfo['description']) ? $socialInfo['description'] : '',
                'socialImageLink' => isset($socialInfo['image_link']) ? $socialInfo['image_link'] : '',
                'analyticsUtmSource' => isset($analyticsInfo['utm_source']) ? $analyticsInfo['utm_source'] : '',
                'analyticsUtmMedium' => isset($analyticsInfo['utm_medium']) ? $analyticsInfo['utm_medium'] : '',
                'analyticsUtmCampaign' => isset($analyticsInfo['utm_campaign']) ? $analyticsInfo['utm_campaign'] : '',
                'analyticsUtmTerm' => isset($analyticsInfo['utm_term']) ? $analyticsInfo['utm_term'] : '',
                'analyticsUtmContent' => isset($analyticsInfo['utm_content']) ? $analyticsInfo['utm_content'] : '',
                'analyticsGClId' => isset($analyticsInfo['gclid']) ? $analyticsInfo['gclid'] : '',
                'analyticsItunesAT' => isset($analyticsInfo['itunes_at']) ? $analyticsInfo['itunes_at'] : '',
                'analyticsItunesCT' => isset($analyticsInfo['itunes_ct']) ? $analyticsInfo['itunes_ct'] : '',
                'analyticsItunesMT' => isset($analyticsInfo['itunes_mt']) ? $analyticsInfo['itunes_mt'] : '8',
                'analyticsItunesPT' => isset($analyticsInfo['itunes_pt']) ? $analyticsInfo['itunes_pt'] : '',
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

    /**
     * @param $placeholders
     * @param $params
     * @return array
     */
    private static function handle_placeholders($placeholders, $params) {

        if(!is_array($placeholders)) {
            $placeholders = array();
        }

        if(!is_array($params)) {
            $params = array();
        }

        $new_placeholders = array();

        foreach ($placeholders as $key => $value) {
            $key = strtoupper($key);
            $new_placeholders["__{$key}__"] = $value;
        }

        return array_merge($params, $new_placeholders);
    }
}

