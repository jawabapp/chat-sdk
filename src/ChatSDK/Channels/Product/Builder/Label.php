<?php
/**
 * Created by PhpStorm.
 * User: ibraheemqanah
 * Date: 2019-03-26
 * Time: 17:58
 */

namespace ChatSDK\Channels\Product\Builder;


class Label
{
    private $labels = [];

    public function add($lang, $label) {

        $this->labels[$lang] = $label;

        return $this;
    }

    public function getLanguages() {
        return array_keys($this->labels);
    }

    public function getLabel($lang) {
        return isset($this->labels[$lang]) ? $this->labels[$lang] : '';
    }

    public static function make(array $labels) {

        $obj = new self();

        if($labels) {
            foreach ($labels as $lang => $label) {
                $obj->add($lang, $label);
            }
        }

        return $obj;

    }
}