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
        $config['base_uri'] = 'https://' . Config::get('host') . '/api/';

        parent::__construct($config);
    }

}