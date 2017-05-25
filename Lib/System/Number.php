<?php

/*
 * Copyright (C) 2013-2014 Hat <hat@maytinhsaigon.com>
 * 
 * This file is part of MTSG OPEN FRAMEWORK.
 * 
 * MTSG OPEN FRAMEWORK can not be copied and/or distributed 
 * without the express permission of MTSG
 */

/**
 * Description of System_Number
 *
 * @author hatmt_000
 */
class System_Number extends System_DataType {

    /**
     *
     */
    const INTEGER_TYPE = 'integer';
    /**
     *
     */
    const FLOAT_TYPE = 'float';
    /**
     *
     */
    const DOUBLE_TYPE = 'double';
    /**
     *
     */
    const NUMERIC_TYPE = 'numeric';
    //
//    const REGION = Config_Parameter::getInstance()->get(Key::DEFAULT_REGION);
//    const STANDARD_REGION = Config_Parameter::getInstance()->get(Key::STANDARD_REGION);
    /**
     *
     */
    const STANDARD_FORMAT = '############';
    /**
     *
     */
    const VI_REGION = 'vi';
    /**
     *
     */
    const US_REGION = 'us';

    /**
     * @var string
     */
    protected $region = NULL;
    /**
     * @var string
     */
    protected $defaultRegion = NULL;
    /**
     * @var string
     */
    protected $baseRegion = self::US_REGION;

    static protected $numberArray = array(
        'vi' => array('không', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín')
    );

    /**
     * @var ArrayObject
     */
    private $tmp;

    /**
     * Initial
     */
    public function init() {
        // Initial
        $this->standardFormat = array(
            K::mask => K::numeric,
            K::digits => Config_Parameter::g(K::STANDARD_DOUBLE_DIGITS),
            K::prefix => '',
            K::suffix => '',
            K::radixPoint => Config_Parameter::g(K::STANDARD_DOUBLE_RADIX_POINT),
            K::groupSeparator => Config_Parameter::g(K::STANDARD_DOUBLE_GROUP_SEPARATOR)
        );

        $this->format = array(
            K::mask => K::numeric,
            K::prefix => '',
            K::suffix => '',
            K::radixPoint => Config_Parameter::g(K::DEFAULT_DOUBLE_RADIX_POINT),
            K::groupSeparator => Config_Parameter::g(K::DEFAULT_DOUBLE_GROUP_SEPARATOR)
        );
    }

    /**
     * @param $value
     * @param array|string $format = [mask=>numeric|currency|decimal|regex, digits=>0|2, prefix=>'', suffix=>'', radixPoint=>.|,groupSeparator=>,|.]|mask
     */
    public function __construct($value, $format = NULL) {

        // Initial
        $this->init();

        if (is_array($format)) {
            $this->format = array_merge($this->format, $format);
        } elseif (is_string($format)) {
            $this->format = array_merge($this->format, $this->extractFormat($format));
        }
        $this->region = Config_Parameter::g(K::DEFAULT_REGION);
        $this->defaultRegion = Config_Parameter::g(K::DEFAULT_REGION);

        $this->tmp = new ArrayObject();
        $this->setValue($this->convertByFormat($value, $this->format, $this->standardFormat));
    }

    public function convertByFormat($value, $fromFormat, $toFormat) {
        $result = str_replace(array(
                $fromFormat[K::groupSeparator],
                $fromFormat[K::radixPoint],
            ),
            array(
                $toFormat[K::groupSeparator],
                $toFormat[K::radixPoint],
            ), $value);

        $result = number_format($result, $toFormat[K::digits], $toFormat[K::radixPoint], $toFormat[K::groupSeparator]);
        $result = preg_replace('/^' . $fromFormat[K::prefix] . '/', $toFormat[K::prefix], $result);
        $result = preg_replace('/' . $fromFormat[K::suffix] . '$/', $toFormat[K::suffix], $result);

        return $result;
    }

    /**
     * @param $value
     * @param array|string $format = [mask=>numeric|currency|decimal|regex, digits=>0|2, prefix=>'', suffix=>'', radixPoint=>.|,groupSeparator=>,|.]|mask
     * @return System_Number
     */
    public static function getInstance($value, $format = NULL) {
        return new self($value, $format);
    }

    /**
     * Get base value
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set base value
     * @param $value
     * @param null $format
     * @return $this|void
     */
    public function setValue($value, $format = NULL) {
        if (!$format) {
            $format = $this->standardFormat;
        }
        $this->value = $this->convertByFormat($value, $format, $this->standardFormat);
    }

    /**
     * @return mixed|string
     */
    public function getRegion() {
        return $this->region;
    }

    /**
     * @param $locale
     * @return $this
     */
    public function setRegion($locale) {
        $this->region = $locale;
        return $this;
    }

    /**
     * @param $pattern
     * @return $this
     */
    public function setPattern($pattern) {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseRegion() {
        return $this->baseRegion;
    }

    /**
     * @param string $baseRegion
     * @return $this
     */
    public function setBaseRegion($baseRegion) {
        $this->baseRegion = $baseRegion;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultRegion() {
        return $this->defaultRegion;
    }

    /**
     * @param string $defaultRegion
     * @return $this
     */
    public function setDefaultRegion($defaultRegion) {
        $this->defaultRegion = $defaultRegion;
        return $this;
    }

    /**
     * @param $locale
     * @return mixed|string
     */
    public function convertTo($locale) {

        $value = $this->value;
        /**
         * Convert to default
         */
        if ($locale != Config_Parameter::getInstance()->get(Key::STANDARD_REGION)) {
            $value = $this->convertToEn();
        }

        /**
         * Convert to require
         */
        switch ($locale) {
            case Config_Parameter::getInstance()->get(Key::STANDARD_REGION):
                return $value;
            case self::VI_REGION:
                return System_Number::getInstance($value, $this->pattern)->convertToVi();
        }
    }

    /**
     * @return mixed|string
     */
    public function convertToEn() {
        switch ($this->region) {
            case Config_Parameter::getInstance()->get(Key::STANDARD_REGION):
                return $this->value;
            case self::VI_REGION:
                return str_replace(array(','), array('.'), $this->value);
        }
    }

    /**
     * Number format function
     * @param null|string $format ex : ###,###,###.00 (us format)
     * @return string
     */
    public function toString($format = NULL) {
        if (!$format) {
//            $format = Config_Parameter::getInstance()->get(Key::DEFAULT_DIGIT_FORMAT);
            $format = $this->format;
        }
        $extractFormat = $this->extractFormat($format);
        $number = $extractFormat[Key::prefix] . number_format($this->getValue() ?: '0', $extractFormat[Key::digits], $extractFormat[Key::radixPoint], $extractFormat[Key::groupSeparator]) . $extractFormat[Key::suffix];
        return $number;
    }

    public function getStandardValue() {
        return $this->toString($this->standardFormat);
    }

    /**
     * @return mixed|string
     */
    public function convertToVi() {
        switch ($this->region) {
            case Config_Parameter::getInstance()->get(Key::STANDARD_REGION):
                return str_replace(array('.'), array(','), $this->value);
            case self::VI_REGION:
                return $this->value;
        }
    }

    /**
     * @param $thousandsChar
     */
    public function extractThousandsCharCallBack($thousandsChar) {
        $this->tmp['FormatExtract'][Key::groupSeparator] = $thousandsChar;
    }

    /**
     * @param $decPointChar
     */
    public function extractDecPointCharCallBack($decPointChar) {
        $this->tmp['FormatExtract'][Key::radixPoint] = $decPointChar;
    }

    /**
     * @param $format
     * @return mixed
     */
    public function extractFormat($format) {
        if (is_string($format)) {
//            $this->tmp = array(
//                'FormatExtract' => array(
//                    Key::digits => 0,
//                    Key::radixPoint => '.',
//                    Key::groupSeparator => '',
//                    Key::prefix => '',
//                    Key::suffix => '',
//                )
//            );
//            preg_replace_callback('/#([\.,])#/', function ($agr1) {
//                $this->extractThousandsCharCallBack($agr1[1]);
//                return $agr1[1];
//            }, $format);
//            preg_replace_callback('/#([\.,])0/', function ($agr1) {
//                $this->extractDecPointCharCallBack($agr1[1]);
//                return $agr1[1];
//            }, $format);
//            $decimalsObj = [];
//            preg_match_all('/0/', $format, $decimalsObj);
//            $this->tmp['FormatExtract'][Key::digits] = count($decimalsObj[0]);
//            return $this->tmp['FormatExtract'];
        } elseif (is_array($format)) {
            return $format;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function toCurrency() {
        return $this->toString(Config_Parameter::g(K::DEFAULT_CURRENCY_FORMAT)) . Config_Parameter::getInstance()->get(Key::DEFAULT_CURRENCY_SYMBOL);
    }

    protected static function _readNumberDozens($so, $full) {
        $result = "";
        $dozens = floor($so / 10);
        $unit = $so % 10;
        if ($dozens > 1) {
            $result = " " . self::$numberArray['vi'][$dozens] . " mươi";
            if ($unit == 1) {
                $result .= " mốt";
            }
        } else if ($dozens == 1) {
            $result = " mười";
            if ($unit == 1) {
                $result .= " một";
            }
        } else if ($full && $unit > 0) {
            $result = " lẻ";
        }
        if ($unit == 5 && $dozens > 1) {
            $result .= " lăm";
        } else if ($unit > 1 || ($unit == 1 && $dozens == 0)) {
            $result .= " " . self::$numberArray['vi'][$unit];
        }
        return $result;
    }

    protected static function _readNumberBlock($so, $full) {
        $result = "";
        $hundred = floor($so / 100);
        $so = $so % 100;
        if ($full || $hundred > 0) {
            $result = " " . self::$numberArray['vi'][$hundred] . " trăm";
            $result .= self::_readNumberDozens($so, true);
        } else {
            $result = self::_readNumberDozens($so, false);
        }
        return $result;
    }

    protected static function _readNumberMillion($number, $full) {
        $result = "";
        $million = floor($number / 1000000);
        $number = $number % 1000000;
        if ($million > 0) {
            $result = self::_readNumberBlock($million, $full) . " triệu";
            $full = true;
        }
        $thousand = floor($number / 1000);
        $number = $number % 1000;
        if ($thousand > 0) {
            $result .= self::_readNumberBlock($thousand, $full) . " nghìn";
            $full = true;
        }
        if ($number > 0) {
            $result .= self::_readNumberBlock($number, $full);
        }
        return $result;
    }

    public static function _convertToText($number) {
        if ($number == 0)
            return self::$numberArray['vi'][0];
        $result = "";
        $suffix = "";
        do {
            $billion = $number % 1000000000;
            $number = floor($number / 1000000000);
            if ($number > 0) {
                $result = self::_readNumberMillion($billion, true) . $suffix . $result;
            } else {
                $result = self::_readNumberMillion($billion, false) . $suffix . $result;
            }
            $suffix = " tỷ";
        } while ($number > 0);
        return $result;
    }

    /**
     * Convert number to text
     * @return string
     */
    public function convertToText() {
        return self::_convertToText($this->getValue());
    }
}
