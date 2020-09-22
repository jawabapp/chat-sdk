<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Symfony\Component\Dotenv\Dotenv;

if(!file_exists(__DIR__.'/.env')) {
    die('.env file not exists' . PHP_EOL);
}

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

function config($key) {
    return isset($_ENV[$key]) ? $_ENV[$key] : '';
}
