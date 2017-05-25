<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 29/12/2016
 * Time: 05:29 PM
 */
abstract class System_DataType {

    /**
     * @var any
     */
    public $value;

    /**
     * @var array|any
     */
    protected $format;

    /**
     * @var array|any
     */
    protected $standardFormat;

    /**
     * Initial
     */
    abstract protected function init();

    /**
     * @return any
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set value with format, if without format, standard format was used
     * @param any $value
     * @param array|any $format
     * @return $this
     */
    public function setValue($value, $format = NULL) {
        if ($format) {
            $this->value = $this->convertByFormat($value, $format, $this->standardFormat);
        } else {
            $this->value = $value;
        }

        return $this;
    }

    /**
     * Set display value, display format was used as default
     * @param $value
     * @return \System_DataType
     */
    public function setDisplayValue($value) {
        return $this->setValue($value, $this->format);
    }

    /**
     * @return mixed
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * @param mixed $format
     * @return $this
     */
    public function setFormat($format) {
        $this->format = $format;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStandardFormat() {
        return $this->standardFormat;
    }

    /**
     * @param mixed $standardFormat
     * @return $this
     */
    public function setStandardFormat($standardFormat) {
        $this->standardFormat = $standardFormat;
        return $this;
    }

    /**
     * Static convert by format
     * @param $value
     * @param $fromFormat
     * @param $toFormat
     * @return mixed
     */
    abstract public function convertByFormat($value, $fromFormat, $toFormat);

    /**
     * Get value as input format, if not display format use as default
     * @param null $format
     * @return any
     */
    public function toString($format = NULL) {
        if (!$format) {
            $format = $this->format;
        }
        return $this->convertByFormat($this->value, $this->standardFormat, $format);
    }

    /**
     * Get value as display format
     * @return string
     */
    function __toString() {
        return $this->toString($this->getFormat());
    }
} 