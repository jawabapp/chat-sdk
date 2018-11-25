<?php
/**
 * Created by PhpStorm.
 * User: qanah
 * Date: 11/14/18
 * Time: 9:49 AM
 */

namespace ChatSDK\Facades;


use ChatSDK\Config\Repository;

class Config extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'config';
    }

    public static function make(array $arr) {

        $arr['host'] = 'chat.jawab.app';

        $repository = new Repository($arr);

        static::swap($repository);

        return $repository;

    }
}