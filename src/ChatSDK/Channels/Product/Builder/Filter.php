<?php
/**
 * Created by PhpStorm.
 * User: ibraheemqanah
 * Date: 2019-03-21
 * Time: 16:06
 */

namespace ChatSDK\Channels\Product\Builder;


use ChatSDK\Channels\Product\FilterChannel;
use Exception;

class Filter
{
    private $filters = array();

    private function add($filter) {

        $this->filters[] = $filter;

        return $this;
    }

    private function validate_name($name) {
        return $name;
    }

    public function checkBoxes($name, Label $label, Options $options) {
        return $this->add([
            'type' => 'check_boxes',
            'name' => $this->validate_name($name),
            'label' => $label,
            'options' => $options
        ]);
    }

    public function switchKey($name, Label $label)
    {
        return $this->add([
            'type' => 'switch_key',
            'name' => $this->validate_name($name),
            'label' => $label
        ]);
    }

    public function toArray($language) {

        $filters = array();

        foreach($this->filters as $filter) {

            $fltr = array();

            foreach($filter as $key => $value) {
                if($value instanceof Label) {
                    $fltr[$key] = $value->getLabel($language);
                } elseif($value instanceof Options) {
                    $fltr[$key] = $value->getOptions($language);
                } else {
                    $fltr[$key] = $value;
                }
            }

            array_push($filters, $fltr);
        }

        return $filters;
    }

    public function build() {

        $languages = array();
        foreach($this->filters as $filter) {
            $languages =  array_merge($languages, $filter['label']->getLanguages());
        }
        $languages = array_unique($languages);

        $values = array();
        foreach ($languages as $language) {
            $values[$language] = $this->toArray($language);
        }

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