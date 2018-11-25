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
use ChatSDK\Facades\Receiver;
use Exception;

class ReceiveChannel
{

    private static function init() {
        Client::make();
    }

    public static function receive(callable $handleMessage, $logs = false) {

        self::init();

        if(!Client::has('client_id')) {
            throw new Exception('invalid client id');
        }

        $mqtt = new phpMQTT(Client::get('host'), Client::get('port'), Client::get('client_id'));

        if(!$mqtt->connect(true, NULL, Client::get('mqtt_username'), Client::get('mqtt_password'))) {
            throw new Exception('Connection failed!');
        }

        $topics[Client::get('topic_prefix') . '/#'] = array(
            "qos" => 1,
            "function" => function ($topic, $message) use ($handleMessage, $logs) {

                try {
                    $payload = json_decode($message, true);

                    if(empty($payload['account_sender_id'])) {
                        return;
                    }

                    if(empty($payload['type']) || $payload['type'] != 'message') {
                        return;
                    }

                    if($logs) {
                        echo "\n";
                        echo "Msg Recieved: " . date("r") . "\n";
                        echo "Topic: {$topic}\n";
                    }

                    Receiver::fetch($topic, $payload['account_sender_id']);

                    if(is_callable($handleMessage)) {
                        call_user_func(
                            $handleMessage,
                            array(
                                'content_type' => $payload['content_type'],
                                'content' => $payload['content'],
                                'mode' => Receiver::get('mode')
                            ),
                            array(
                                'nickname' => Receiver::get('nickname'),
                                'phone' => Receiver::get('phone'),
                            )
                        );
                    }

                } catch (Exception $e) {
                    if($logs) {
                        echo "Error: " . $e->getMessage() . "\n";
                    }
                }

            }
        );

        $mqtt->subscribe($topics, 1);

        while($mqtt->proc()) { }

        $mqtt->close();
    }
}
