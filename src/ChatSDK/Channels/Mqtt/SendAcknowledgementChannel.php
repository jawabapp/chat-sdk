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

class SendAcknowledgementChannel
{

    private static function init() {
        Client::make();
    }

    public static function send($phone, $topic, $receiver_id, $message_id, $content_type = "delivered", $name = null, $avatar = null) {

        self::init();

        if(!Client::has('client_id')) {
            throw new Exception('invalid client id');
        }

        if(!in_array($content_type, ['delivered', 'read'])){
            throw new Exception('The content type must be (delivered,read)');
        }

        if(!$receiver_id) {
            throw new Exception('invalid receiver id');
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

        $mqtt->publish("usr/{$receiver_id}", json_encode([
            "published_form_sdk" => true,
            "sender_id" => Sender::get('sender_id'),
            "account_sender_id" => Sender::get('account_sender_id'),
            "account_sender_nickname" => Sender::get('account_sender_nickname'),
            "account_sender_avatar" => Sender::get('account_sender_avatar'),
            "chat_id" => $topic,
            "message_id" => $message_id,
            "content_type" => $content_type,
            "created_at" => time(),
            "type" => "ack"
        ]), 0);

        $mqtt->close();

    }
}