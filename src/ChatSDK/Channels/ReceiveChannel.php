<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/14/18
 * Time: 12:14 AM
 */

namespace ChatSDK\Channels;

use Bluerhinos\phpMQTT;

class ReceiveChannel
{
    public static function receive(callable $handleMessage, $logs = false) {

        $server = "chat.jawab.app";     // change if necessary
        $port = 1883;                     // change if necessary
        $username = "";                   // set your username
        $password = "";                   // set your password

        $client_id = "phpMQTT-subscriber"; // make sure this is unique for connecting to sever - you could use uniqid()

        $mqtt = new phpMQTT($server, $port, $client_id);

        if(!$mqtt->connect(true, NULL, $username, $password)) {
            exit(1);
        }

        $topics['grp/ser-1/#'] = array(
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