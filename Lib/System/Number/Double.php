<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 29/12/2016
 * Time: 11:41 AM
 */
class System_Number_Double extends System_Number {

    public function init() {
        parent::init();

        $this->format = array(
            K::mask => K::numeric,
            K::digits => Config_Parameter::g(K::DEFAULT_DOUBLE_DIGITS),
            K::prefix => '',
            K::suffix => '',
            K::radixPoint => Config_Parameter::g(K::DEFAULT_DOUBLE_RADIX_POINT),
            K::groupSeparator => Config_Parameter::g(K::DEFAULT_DOUBLE_GROUP_SEPARATOR)
        );
    }

    /**
     * @param $value
     * @param null $format
     * @return System_Number|System_Number_Double
     */
    public static function getInstance($value, $format = NULL) {
        return new self($value, $format);
    }

} 