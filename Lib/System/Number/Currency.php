<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 29/12/2016
 * Time: 11:41 AM
 */
class System_Number_Currency extends System_Number {

    /**
     * Initial
     */
    public function init() {
        parent::init();

        $this->format = [
            Key::mask => Key::numeric,
            Key::digits => Config_Parameter::g(Key::DEFAULT_CURRENCY_DIGITS),
            Key::prefix => Config_Parameter::g(Key::DEFAULT_PREFIX_CURRENCY_SYMBOL),
            Key::suffix => Config_Parameter::g(Key::DEFAULT_SUFFIX_CURRENCY_SYMBOL),
            Key::radixPoint => Config_Parameter::g(Key::DEFAULT_DOUBLE_RADIX_POINT),
            Key::groupSeparator => Config_Parameter::g(Key::DEFAULT_DOUBLE_GROUP_SEPARATOR)
        ];
    }

    /**
     * @param $value
     * @param null $format
     * @return System_Number|System_Number_Currency
     */
    public static function getInstance($value, $format = NULL) {
        return new self($value, $format);
    }


} 