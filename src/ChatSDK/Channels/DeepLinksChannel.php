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
     * @param $desktopLink
     * @param $accountSlug
     * @param array $placeholders
     * @param array $analyticsInfo
     * @param array $socialInfo
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_account_link($desktopLink, $accountSlug, $placeholders = array(), $analyticsInfo = array(), $socialInfo = array()) {

        Client::make();

        $link = self::handle_url($desktopLink, self::handle_placeholders($placeholders, array(
            'service_id' => Client::get('id'),
            'mode' => 'account',
            'slug' => urlencode($accountSlug),
        )));

        return self::generate($link, $analyticsInfo, $socialInfo);
    }

    /**
     * @param $desktopLink
     * @param $accountSlug
     * @param array $referrerMessage
     * @param null $webUuid
     * @param array $placeholders
     * @param array $analyticsInfo
     * @param array $socialInfo
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_one_to_one_chat_link($desktopLink, $accountSlug, $referrerMessage = array(), $webUuid = null, $placeholders = array(), $analyticsInfo = array(), $socialInfo = array()) {

        Client::make();

        $enable_message = false;
        $notification_message = array();

        if($referrerMessage) {
            $enable_message = true;
            $notification_message = array(
                //base
                'deep_link' => empty($referrerMessage['deep_link']) ? "" : urlencode($referrerMessage['deep_link']),
                'language' => empty($referrerMessage['language']) ? "en" : $referrerMessage['language'],
                'notification_title' => empty($referrerMessage['notification_title']) ? "" : $referrerMessage['notification_title'],
                'button_title' => empty($referrerMessage['button_title']) ? "" : $referrerMessage['button_title'],
                'description' => empty($referrerMessage['description']) ? "" : $referrerMessage['description'],

                //fonts
                'bold_expert' => empty($referrerMessage['bolds']['expert']) ? false : $referrerMessage['bolds']['expert'],
                'bold_description' => empty($referrerMessage['bolds']['description']) ? false : $referrerMessage['bolds']['description'],
                'bold_button' => empty($referrerMessage['bolds']['button']) ? false : $referrerMessage['bolds']['button'],

                //colors
                'color_text_expert' => empty($referrerMessage['colors']['text_expert']) ? "#000000" : $referrerMessage['colors']['text_expert'],
                'color_text_description' => empty($referrerMessage['colors']['text_description']) ? "#000000" : $referrerMessage['colors']['text_description'],
                'color_text_button' => empty($referrerMessage['colors']['text_button']) ? "#ffffff" : $referrerMessage['colors']['text_button'],
                'color_background' => empty($referrerMessage['colors']['background']) ? "#ffffff" : $referrerMessage['colors']['background'],
                'color_button' => empty($referrerMessage['colors']['button']) ? "#24db27" : $referrerMessage['colors']['button'],

                //expert
                'expert_enabled' => empty($referrerMessage['expert']['name']) ? false : true,
                'expert_image' => empty($referrerMessage['expert']['image']) ? "" : $referrerMessage['expert']['image'],
                'expert_name' => empty($referrerMessage['expert']['name']) ? "" : $referrerMessage['expert']['name'],
                'expert_title' => empty($referrerMessage['expert']['title']) ? "" : $referrerMessage['expert']['title'],
                'expert_subtitle' => empty($referrerMessage['expert']['subtitle']) ? "" : $referrerMessage['expert']['subtitle'],

                //image
                'image_enabled' => empty($referrerMessage['image']['url']) ? false : true,
                'image' => empty($referrerMessage['image']['url']) ? "" : $referrerMessage['image']['url'],
                'height' => empty($referrerMessage['image']['height']) ? "" : $referrerMessage['image']['height'],
                'width' => empty($referrerMessage['image']['width']) ? "" : $referrerMessage['image']['width'],
            );
        }

        $link = self::handle_url($desktopLink, self::handle_placeholders(array_merge(
            $placeholders,
            $notification_message
        ), array(
            'service_id' => Client::get('id'),
            'mode' => 'one-to-one-chat',
            'slug' => urlencode($accountSlug),
            'enable_message' => $enable_message,
            'web_uuid' => $webUuid,
        )));

        return self::generate($link, $analyticsInfo, $socialInfo);
    }

    /**
     * @param $desktopLink
     * @param $successRedirectUri
     * @param $failedRedirectUri
     * @param null $webUuid
     * @param array $placeholders <p>
     * Placeholder array example.
     * language: this language [ar|en]
     * logo: this logo
     * title: this title
     * subtitle: this subtitle
     * image: this image
     * button_text: this button text
     * button_text_color: this button text colors
     * button_color: this button color
     * gradient_start_color: this background color gradient start
     * gradient_end_color: this background color gradient end
     * </p>
     * @param array $analyticsInfo
     * @param array $socialInfo
     * @param null $closeRedirectUri
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_reworded_ads_link($desktopLink, $successRedirectUri, $failedRedirectUri, $webUuid = null, $placeholders = array(), $analyticsInfo = array(), $socialInfo = array(), $closeRedirectUri = null) {

        Client::make();

        $link = self::handle_url($desktopLink, self::handle_placeholders($placeholders, array(
            'service_id' => Client::get('id'),
            'mode' => 'reworded-ads',
            'success_redirect_uri' => urlencode($successRedirectUri),
            'failed_redirect_uri' => urlencode($failedRedirectUri),
            'close_redirect_uri' => $closeRedirectUri ? urlencode($closeRedirectUri) : null,
            'web_uuid' => $webUuid,
        )));

        return self::generate($link, $analyticsInfo, $socialInfo);
    }

    /**
     * @param $desktopLink
     * @param array $placeholders
     * @param array $analyticsInfo
     * @param array $socialInfo
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException|Exception
     */
    public static function generate_broadcast_link($desktopLink, $placeholders = array(), $analyticsInfo = array(), $socialInfo = array()) {

        Client::make();

        $broadcast = Client::get('broadcast');

        if(empty($broadcast)) {
            throw new Exception('The broadcast info was invalid.');
        }

        $link = self::handle_url($desktopLink, self::handle_placeholders($placeholders, array(
            'service_id' => Client::get('id'),
            'mode' => 'broadcast',
            'broadcast_user_id' => $broadcast['user_id'],
            'broadcast_account_id' => $broadcast['id'],
        )));

        return self::generate($link, $analyticsInfo, $socialInfo);
    }

    /**
     * @param $desktopLink
     * @param $redirectUri
     * @param $webUuid
     * @param array $placeholders
     * @param array $analyticsInfo
     * @param array $socialInfo
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function generate_webview_link($desktopLink, $redirectUri, $webUuid = null, $placeholders = array(), $analyticsInfo = array(), $socialInfo = array()) {

        Client::make();

        $link = self::handle_url($desktopLink, self::handle_placeholders($placeholders, array(
            'service_id' => Client::get('id'),
            'mode' => 'webview',
            'redirect_uri' => urlencode($redirectUri),
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
     * @param array $placeholders <p>
     * Placeholder array example.
     * language: [ar|en] this will force the chat message to be rtl|ltr
     * category: this will will appear in top of the subscription screen
     * expert_image: this expert image
     * expert_name: this expert name
     * expert_title: this expert title
     * expert_subtitle: this expert subtitle
     * hashtag: this auto follow hashtag
     * hashtag_link: optional
     * </p>
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
     * @param array $placeholders <p>
     * Placeholder array example.
     * language: [ar|en] this will force the chat message to be rtl|ltr
     * category: this will will appear in top of the subscription screen
     * expert_image: this expert image
     * expert_name: this expert name
     * expert_title: this expert title
     * expert_subtitle: this expert subtitle
     * hashtag: this auto follow hashtag
     * hashtag_link: optional
     * </p>
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
     * @param array $placeholders <p>
     * Placeholder array example.
     * language: [ar|en] this will force the chat message to be rtl|ltr
     * category: this will will appear in top of the subscription screen
     * expert_image: this expert image
     * expert_name: this expert name
     * expert_title: this expert title
     * expert_subtitle: this expert subtitle
     * hashtag: this auto follow hashtag
     * hashtag_link: optional
     * </p>
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
     * @param array $placeholders <p>
     * Placeholder array example.
     * language: [ar|en] this will force the chat message to be rtl|ltr
     * category: this will will appear in top of the subscription screen
     * expert_image: this expert image
     * expert_name: this expert name
     * expert_title: this expert title
     * expert_subtitle: this expert subtitle
     * hashtag: this auto follow hashtag
     * hashtag_link: optional
     * </p>
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
            'service_id' => Client::get('id'),
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

