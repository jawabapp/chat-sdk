<?php
/**
 * Created by PhpStorm.
 * User: ibraheemqanah
 * Date: 2019-03-21
 * Time: 16:06
 */

namespace ChatSDK\Channels\Product\Builder;


use ChatSDK\Channels\Product\Builder\Preparer\InputData;
use ChatSDK\Channels\Product\FilterChannel;
use Exception;

class Filters
{
    private $filters = array();
    private $orders = array();

    public function checkBoxes($name, Label $label, Options $options) {
        return $this->add([
            'type' => 'check_boxes',
            'name' => $name,
            'label' => $label,
            'options' => $options
        ]);
    }

    public function switchKey($name, Label $label)
    {
        return $this->add([
            'type' => 'switch_key',
            'name' => $name,
            'label' => $label
        ]);
    }

    public function rangeSelector($min_name, $min_value, $max_name, $max_value, Label $label, $currency = "$")
    {
        return $this->add([
            'type' => 'range_selector',
            'name' => "{$min_name}_{$max_name}",
            'min_name' => $min_name,
            'min_value' => floatval($min_value),
            'max_name' => $max_name,
            'max_value' => floatval($max_value),
            'currency' => $currency,
            'label' => $label
        ]);
    }

    /**
     *
     * @response {
     *      "language_ar_country_jo":  {
     *          "expression": {
     *              "language": ['ar']
     *              "country": ['jo']
     *          }
     *          "data": {...}
     *      },
     *      "default": {
     *          "data": {...}
     *      }
     *  }
     *
     * @param bool $debug
     * @return mixed
     */
    public function build($debug = false) {

        $values = array();
        $default = array();
        $notDefault = array();

        foreach ($this->getFilters() as $filter) {
            foreach ($filter as $lang => $filterLangs) {
                foreach ($filterLangs as $country => $data) {
                    if($country === 'default') {

                        $default[$data['label']][$lang] = $data;

                        if($lang === 'en') {
                            $values["default"]['data'][] = $data;
                        } else {

                            //Need to check

                            $filterName = strtolower("language_{$lang}");

                            if(empty($values[$filterName]['expression']['language']) || !in_array($lang, $values[$filterName]['expression']['language'])){
                                $values[$filterName]['expression']['language'][] = $lang;
                            }

                            $values[$filterName]['data'][] = $data;
                        }
                    } else {

                        $notDefault[$data['label']] = $data['label'];

                        $filterName = strtolower("language_{$lang}_country_{$country}");

                        $values[$filterName]['expression']['language'][] = $lang;
                        $values[$filterName]['expression']['country'][] = $country;
                        $values[$filterName]['data'][] = $data;
                    }
                }
            }
        }

        foreach ($notDefault as $not) {
            if(isset($default[$not])) {
                unset($default[$not]);
            }
        }

        foreach ($values as $name => &$value) {
            if($name != 'default') {
               foreach ($default as $label => $itemLang) {
                   foreach ($itemLang as $lang => $item) {
                       if(in_array($lang, $value['expression']['language'])) {
                           $value['data'][] = $item;
                       }
                   }
               }
            }
        }

        foreach ($values as $name => &$value) {
            $value['data'] = $this->finalOrder($value['data']);
        }

        if($debug) {
            error_log(
                json_encode($values)
            );
        }

        try {
             return FilterChannel::build($values);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * @return Filter[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    private function finalOrder($data) {

        $out = array();

        foreach ($data as $item) {

            if(isset($item['options'])) {

                $outOptions = array();

                foreach ($item['options'] as $option) {
                    $outOptions[$this->orders[$item['name']]['option_orders'][$option['value']]] = $option;
                }

                ksort($outOptions);

                $item['options'] = array_values($outOptions);
            }

            $out[$this->orders[$item['name']]['order']] = $item;
        }

        ksort($out);

        return array_values($out);
    }

    private function add($params) {

        $inputData = new InputData();

        $name = isset($params['name']) ? $params['name'] : null;
        $label = isset($params['label']) ? $params['label'] : null;
        $options = isset($params['options']) ? $params['options'] : null;

        $countries = [];
        $allDefaults = [];
        $isDefault = false;

        if ($label && $label instanceof Label) {

            $this->orders[$name]['order'] = count($this->getFilters()) + 1;

            foreach ($label->getLanguages() as $lang) {
                if ($options && $options instanceof Options) {

                    $this->orders[$name]['option_orders'] = $options->getOrders();

                    foreach ($options->getOptions() as $optionVal => $option) {
                        if ($option && $option instanceof Option) {
                            foreach ($option->getConditions() as $type => $condition) {
                                $isDefault = true;
                                switch ($type) {
                                    case 'country':
                                        foreach ($condition as $country) {
                                            $countries[$country] = $country;
                                            $inputData->addCountry($lang, $country, $this->toArray($params, $lang, $option, $optionVal));
                                        }
                                        break;
                                }
                            }
                        } elseif ($option && $option instanceof Label) {
                            $allDefaults[$lang][] = $this->toArray($params, $lang, $option, $optionVal);
                        }
                    }
                } else {
                     $inputData->addCountry($lang, 'default', $this->toArray($params, $lang));
                }
            }
        }

        foreach ($allDefaults as $language => $defaults) {
            if($countries) {
                foreach ($countries as $country) {
                    foreach ($defaults as $default) {
                        $inputData->addCountry($language, $country, $default);
                    }
                }
            } else {
                foreach ($defaults as $default) {
                    $inputData->addCountry($language, 'default', $default);
                }
            }

            if($isDefault) {
                foreach ($defaults as $default) {
                    $inputData->addCountry($language, 'default', $default);
                }
            }
        }

        $this->filters[] = $inputData->getData();

        return $this;
    }

    private function toArray($filter, $language, $option = null, $optionVal = '') {

        $output = array();

        foreach($filter as $key => $value) {
            if($value instanceof Label) {
                $output[$key] = $value->getLabel($language);
            } elseif($value instanceof Options) {
                if($option) {
                    if($option instanceof Option) {
                        $output[$key] = [
                            [
                                'value' => $optionVal,
                                'label' => $option->getLabel()->getLabel($language),
                            ]
                        ];
                    } elseif ($option instanceof Label) {
                        $output[$key] = [
                            [
                                'value' => $optionVal,
                                'label' => $option->getLabel($language),
                            ]
                        ];
                    }
                } else {
                    $output[$key] = $value->getOptions($language);
                }
            } else {
                $output[$key] = $value;
            }
        }

        return $output;

    }
}
