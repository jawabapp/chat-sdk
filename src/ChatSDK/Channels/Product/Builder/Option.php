<?php
/**
 * Created by PhpStorm.
 * User: ibraheemqanah
 * Date: 2019-03-26
 * Time: 17:58
 */

namespace ChatSDK\Channels\Product\Builder;


class Option
{
    private $label;
    private $conditions = [];

    /**
     * @return Label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param $label
     */
    public function setLabel(Label $label)
    {
        $this->label = $label;
    }

    /**
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param array $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }

    public function getOption($lang) {
        return $this->getLabel()->getLabel($lang);
    }

    public static function make(Label $label, array $condition) {

        $obj = new self();
        $obj->setLabel($label);
        $obj->setConditions($condition);

        return $obj;

    }

}
