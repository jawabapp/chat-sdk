<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/14/18
 * Time: 12:14 AM
 */

namespace ChatSDK\Channels;

use ChatSDK\Facades\Client;
use ChatSDK\Facades\Receiver;
use ChatSDK\Support\MyPhpMQTT;
use Exception;

class ReceiveChannel
{

    private static function init() {
        Client::make();
    }

    public static function receive(callable $handleMessage, $logs = false, $debug = false) {

        self::init();

        if(!Client::has('receive_client_id')) {
            throw new Exception('invalid client id');
        }

        $mqtt = new MyPhpMQTT(Client::get('host'), Client::get('port'), Client::get('receive_client_id'));

        $mqtt->debug = $debug;

        if(!$mqtt->connect(false, NULL, Client::get('mqtt_username'), Client::get('mqtt_password'))) {
            throw new Exception('Connection failed!');
        }

        $topics[Client::get('topic_prefix') . '/#'] = array(
            "qos" => 0,
            "function" => function ($topic, $message) use ($handleMessage, $logs) {

                if($logs) {
                    echo "Msg Recieved: " . date("r") . "\n";
                    echo "Topic: {$topic}" . "\n";
                }

                try {

                    $payload = json_decode($message, true);

                    if(empty($payload)) {

                        if($logs) {
                            echo "Invalid Payload" . "\n";
                        }

                        return;
                    }

                    if(empty($payload['account_sender_id'])) {

                        if($logs) {
                            echo "Invalid Payload [account_sender_id] is empty" . "\n";
                        }

                        return;
                    }

                    if(empty($payload['type']) || $payload['type'] != 'message') {

                        if($logs) {
                            echo "Ignore Payload the type is not [message]" . "\n";
                        }

                        return;
                    }

                    if($logs) {
                        echo "Start Payload Processing" . "\n";
                    }

                    Receiver::fetch($topic, $payload['account_sender_id']);

                    if(is_callable($handleMessage)) {
                        call_user_func(
                            $handleMessage,
                            array(
                                //topic info
                                'topic' => $topic,
                                'message_id' => $payload['message_id'],

                                //content info
                                'content_type' => $payload['content_type'],
                                'content' => $payload['content'],

                                //ref info
                                'ref_user_id' => Receiver::get('ref_user_id'),
                                'ref_topic_id' => Receiver::get('ref_topic_id'),
                                'ref_language' => Receiver::get('ref_language'),
                            ),
                            array(
                                //sender info
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

        $mqtt->subscribe($topics, 0);

        while($mqtt->proc()) { }

        $mqtt->close();
    }
}
