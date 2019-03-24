<?php
/**
 * Created by PhpStorm.
 * User: ibraheemqanah
 * Date: 2019-03-21
 * Time: 16:06
 */

namespace ChatSDK\Channels\Product\Builder;


use ChatSDK\Channels\Product\FilterChannel;
use ChatSDK\Config\Repository;
use Exception;

class Filter
{
    private static $filters;

    public static function get_instance()
    {
        if(is_null(self::$filters)) {
            self::$filters = (new Repository);
        }

        return self::$filters;
    }

    private static function prepValues($values) {

        $out = [];

        foreach ($values as $key => $value) {
            array_push($out, [
                'value' => is_numeric($key) ? $value : $key,
                'label' => $value,
            ]);
        }

        return $out;
    }

    public static function checkBoxes($name, $label, $values) {
        self::get_instance()->set($name, [
            'type' => 'check_boxes',
            'name' => $name,
            'label' => $label,
            'values' => self::prepValues($values)
        ]);
    }

    public static function switchKey($name, $label) {
        self::get_instance()->set($name, [
            'type' => 'switch_key',
            'name' => $name,
            'label' => $label
        ]);
    }

    public static function build() {

        $values = array_values(
            self::get_instance()->all()
        );

        error_log(
            json_encode($values)
        );

        try {
            return FilterChannel::build($values);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}