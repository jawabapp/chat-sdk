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
use Exception;

class ReceiveChannel
{
    public static function receive(callable $handleMessage, $logs = false) {

        Client::make();

        $server = "chat.jawab.app";
        $port = 1883;
        $username = Client::get('mqtt_username');
        $password = Client::get('mqtt_password');

        $client_id = uniqid("service_" . Client::get('id') . "_");

        $mqtt = new phpMQTT($server, $port, $client_id);

        if(!$mqtt->connect(false, NULL, $username, $password)) {
            throw new Exception('Connection failed!');
        }

        $topics['grp/srv-' . Client::get('id') . '/#'] = array(
            "qos" => 1,
            "function" => function ($topic, $message) use ($handleMessage, $logs) {

                if($logs) {
                    echo "Msg Recieved: " . date("r") . "\n";
                    echo "Topic: {$topic}\n\n";
                    echo "\t$message\n\n";
                }

                $sender = "";

                if(is_callable($handleMessage)){
                    call_user_func($handleMessage, $message, $sender);
                }
            }
        );

        $mqtt->subscribe($topics, 1);

        while($mqtt->proc()) { }

        $mqtt->close();
    }
}
