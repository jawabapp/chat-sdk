<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/14/18
 * Time: 12:14 AM
 */

namespace ChatSDK\Channels\Mqtt;

use ChatSDK\Facades\Client;
use ChatSDK\Facades\Sender;
use ChatSDK\Support\MyPhpMQTT;
use Exception;

class SendChannel
{

    private static function init() {
        Client::make();
    }

    public static function send($phone, $topic, $content_type, $content, $name = null, $avatar = null, $created_at = null, $message_id = null) {

        self::init();

        if(!Client::has('client_id')) {
            throw new Exception('invalid client id');
        }

        if(!in_array($content_type, ['text', 'image', 'subscription', 'premium', 'force_subscription'])){
            throw new Exception('The content type must be (text,image,subscription,premium,force_subscription)');
        }

        if (strpos($topic, Client::get('topic_prefix') . '/') !== 0) {
            throw new Exception('Invalid topic prefix');
        }

        Sender::fetch($phone, $topic, $name, $avatar);

        if(!Sender::has('sender_id')) {
            throw new Exception('invalid sender id');
        }

        $mqtt = new MyPhpMQTT(Client::get('host'), Client::get('port'), Client::get('client_id'));

        if(!$mqtt->connect(true, NULL, Client::get('mqtt_username'), Client::get('mqtt_password'))) {
            throw new Exception('Connection failed!');
        }

        $mqtt->publish($topic, json_encode([
            "published_form_sdk" => true,
            "sender_id" => Sender::get('sender_id'),
            "account_sender_id" => Sender::get('account_sender_id'),
            "account_sender_nickname" => Sender::get('account_sender_nickname'),
            "account_sender_avatar" => Sender::get('account_sender_avatar'),
            "chat_id" => $topic,
            "message_id" => $message_id ? $message_id : uniqid($topic, true),
            "content" => $content,
            "content_type" => $content_type,
            "created_at" => empty($created_at) ? time() : $created_at,
            "type" => "message"
        ]), 0);

        $mqtt->close();

    }
}
