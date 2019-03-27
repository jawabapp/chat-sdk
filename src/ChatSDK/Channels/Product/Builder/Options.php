<?php
/**
 * Created by PhpStorm.
 * User: ibraheemqanah
 * Date: 2019-03-26
 * Time: 17:58
 */

namespace ChatSDK\Channels\Product\Builder;


class Options
{
    private $options = array();

    public function add($value, Label $label) {

        $this->options[$value] = $label;

        return $this;
    }

    public function getOptions($lang) {

        $options = array();

        foreach ($this->options as $option => $option_label) { /** @var $option_label Label */
            array_push($options,[
                'value' => $option,
                'label' => $option_label->getLabel($lang),
            ]);
        }

        return $options;
    }

    public static function make(array $options) {

        $obj = new self();

        if($options) {
            foreach ($options as $value => $label) {
                $obj->add($value, $label);
            }
        }

        return $obj;

    }
}