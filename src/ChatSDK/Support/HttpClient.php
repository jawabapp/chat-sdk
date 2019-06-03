<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 12/2/18
 * Time: 12:42 PM
 */

namespace ChatSDK\Support;


use ChatSDK\Facades\Config;
use GuzzleHttp\Client;

class HttpClient extends Client
{

    public function __construct(array $config = [])
    {

        $http = 'https';

        if(Config::has('not_secure') === true) {
            $http = 'http';
        }

        $host = Config::get('host');

        $config['base_uri'] = "{$http}://{$host}/";

        parent::__construct($config);
    }

}