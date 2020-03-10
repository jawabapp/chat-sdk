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
    private $orders = array();

    private function add($value, $label, $order) {
        $this->options[$value] = $label;
        $this->orders[$value] = $order;
        return $this;
    }

    public function getOptions($lang = null) {

        if(is_null($lang)) {
            return $this->options;
        }

        $options = array();

        foreach ($this->options as $option => $option_label) {

            if($option_label instanceof Label) {
                array_push($options,[
                    'value' => $option,
                    'label' => $option_label->getLabel($lang),
                ]);
            }

            if ($option_label instanceof Option) {
                array_push($options,[
                    'value' => $option,
                    'label' => $option_label->getOption($lang),
                ]);
            }
        }

        return $options;
    }

    public static function make(array $options) {

        $obj = new self();

        if($options) {
            $order = 0;
            foreach ($options as $value => $option_label) {
                $order++;
                $obj->add($value, $option_label, $order);
            }
        }

        return $obj;

    }

    /**
     * @return array
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
