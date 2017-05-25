<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 29/12/2016
 * Time: 11:40 AM
 */
class System_String extends System_DataType {

    protected function init() {
        // TODO: Implement init() method.
    }

    /**
     * Constructor
     * @param $value
     * @param null $format
     */
    function __construct($value, $format = NULL) {
        $this->value = $value;
    }

    /**
     * Get instance object
     * @param $value
     * @param null $format
     * @return System_String
     */
    public static function getInstance($value, $format = NULL) {
        return new self($value, $format);
    }


    /**
     * Static convert by format
     * @param $value
     * @param $fromFormat
     * @param $toFormat
     * @return mixed
     */
    public function convertByFormat($value, $fromFormat, $toFormat) {
        return $this->value;
    }
}