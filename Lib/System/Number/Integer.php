<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 29/12/2016
 * Time: 11:41 AM
 */
class System_Number_Integer extends System_Number {

    public function init() {
        parent::init();

        $this->standardFormat = [
            Key::mask => Key::numeric,
            Key::digits => 0,
            Key::prefix => '',
            Key::suffix => '',
            Key::radixPoint => '',
            Key::groupSeparator => Config_Parameter::g(Key::STANDARD_NUMBER_GROUP_SEPARATOR)
        ];

        $this->format = [
            Key::mask => Key::numeric,
            Key::digits => 0,
            Key::prefix => '',
            Key::suffix => '',
            Key::radixPoint => '',
            Key::groupSeparator => Config_Parameter::g(Key::DEFAULT_NUMBER_GROUP_SEPARATOR)
        ];
    }

    public function __construct($value, $format = NULL) {
        parent::__construct($value, $format);
    }

    /**
     * @param $value
     * @param null $format
     * @return System_Number|System_Number_Integer
     */
    public static function getInstance($value, $format = NULL) {
        return new self($value, $format);
    }


}