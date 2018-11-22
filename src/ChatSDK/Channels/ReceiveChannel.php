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

class ReceiveChannel
{

    private static function init() {
        Client::make();
    }

    public static function receive(callable $handleMessage, $logs = false) {

        self::init();

        $server = "chat.jawab.app";
        $port = 1883;
        $username = Client::get('mqtt_username');
        $password = Client::get('mqtt_password');

        $client_id = uniqid("service_" . Client::get('id') . "_");

        $mqtt = new phpMQTT($server, $port, $client_id);

        if(!$mqtt->connect(true, NULL, $username, $password)) {
            throw new Exception('Connection failed!');
        }

        $topics['grp/srv-' . Client::get('id') . '/#'] = array(
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

                    Sender::fetch($topic, $payload['account_sender_id']);

                    if(is_callable($handleMessage)) {
                        call_user_func(
                            $handleMessage,
                            array(
                                'content_type' => $payload['content_type'],
                                'content' => $payload['content'],
                                'mode' => Sender::get('mode')
                            ),
                            array(
                                'nickname' => Sender::get('nickname'),
                                'phone' => Sender::get('phone'),
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
