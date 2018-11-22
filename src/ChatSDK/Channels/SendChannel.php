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

class SendChannel
{

    private static function init() {
        Client::make();
    }

    public static function send($msg) {

        self::init();

        $server = "chat.jawab.app";
        $port = 1883;
        $username = Client::get('mqtt_username');
        $password = Client::get('mqtt_password');

        $client_id = uniqid("service_" . Client::get('id') . "_");

        $mqtt = new phpMQTT($server, $port, $client_id);

        if($mqtt->connect(true, NULL, $username, $password)) {
            $mqtt->publish("bluerhinos/phpMQTT/examples/publishtest", "Hello World! at " . date("r"), 0);
            $mqtt->close();
        } else {
            echo "Time out!\n";
        }

    }
}