<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/14/18
 * Time: 12:14 AM
 */

namespace ChatSDK\Channels;

use Bluerhinos\phpMQTT;
use ChatSDK\Facades\Client;
use ChatSDK\Facades\Sender;
use Exception;

class SendChannel
{

    private static function init() {
        Client::make();
    }

    public static function send(int $ref_id, int $sender_id, string $topic, string $content_type, string $content) {

        self::init();

        if(!Client::has('client_id')) {
            throw new Exception('invalid client id');
        }

        if(!in_array($content_type, ['text', 'image'])){
            throw new Exception('The content type must be (text,image)');
        }

        if (strpos($topic, Client::get('topic_prefix') . '/') !== 0) {
            throw new Exception('Invalid topic prefix');
        }

        Sender::fetch($ref_id, $sender_id, $topic);

        if(!Sender::has('sender_id')) {
            throw new Exception('invalid sender id');
        }

        $mqtt = new phpMQTT(Client::get('host'), Client::get('port'), Client::get('client_id'));

        if(!$mqtt->connect(true, NULL, Client::get('mqtt_username'), Client::get('mqtt_password'))) {
            throw new Exception('Connection failed!');
        }

        $chat_id = str_replace(Client::get('topic_prefix') . '/', '', $topic);

        $mqtt->publish($topic, json_encode([
            "sender_id" => Sender::get('sender_id'),
            "account_sender_id" => Sender::get('account_sender_id'),
            "account_sender_nickname" => Sender::get('account_sender_nickname'),
            "account_sender_avatar" => Sender::get('account_sender_avatar'),
            "chat_id" => $chat_id,
            "message_id" => $chat_id . "_" . time(),
            "content" => $content,
            "content_type" => $content_type,
            "created_at" => time(),
            "type" => "message"
        ]), 0);

        $mqtt->close();

    }
}