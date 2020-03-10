<?php


namespace ChatSDK\Channels\Product\Builder\Preparer;


class InputData
{
    private $data = [];

    public function addLanguage($lang, $data) {
        if (isset($this->data[$lang])) {
            $this->data[$lang] = $this->array_merge_recursive_simple($this->data[$lang], $data);
        } else {
            $this->data[$lang] = $data;
        }
    }

    public function addCountry($lang, $country, $data) {
        if (isset($this->data[$lang][$country])) {
            $this->data[$lang][$country] = $this->array_merge_recursive_simple($this->data[$lang][$country], $data);
        } else {
            $this->data[$lang][$country] = $data;
        }
    }

    private function array_merge_recursive_simple($array1, $array2)
    {
        foreach ($array1 as $k => &$v) {
            if(isset($array2[$k])) {
                if (is_array($v)) {
                    $v = array_merge($v, $array2[$k]);
                }
            }
        }

        return $array1;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
