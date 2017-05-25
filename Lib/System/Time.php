<?php

/**
 * Created by PhpStorm.
 * User: Hat
 * Date: 1/28/2015
 * Time: 7:14 PM
 */
class System_Time extends System_Date {

    /**
     * @param null $time
     * @param null $format
     */
    public function __construct($time = null, $format = NULL) {
        if ($format === NULL) {
            $format = Config_Parameter::getInstance()->get(Key::DB_TIME_FORMAT);

        }
        if (!$time) {
            $time = System_Date::getInstance()->toString($format);
        }
        parent::__construct(self::getMinDate()->toString(Config_Parameter::getInstance()->get(Key::DB_DATE_FORMAT)) . ' ' . $time, Config_Parameter::getInstance()->get(Key::DB_DATE_FORMAT) . ' ' . $format);
    }

    /**
     * @param null $time
     * @param null $format
     * @return System_Date|System_Time
     */
    public static function getInstance($time = null, $format = NULL) {
        if ($format === NULL) {
            $format = Config_Parameter::getInstance()->get(Key::DB_TIME_FORMAT);
        }
        return new self($time, $format);
    }

    /**
     * @param null $format
     * @return bool|string
     */
    public function toString($format = NULL) {
        if ($format === NULL) {
            $format = Config_Parameter::getInstance()->get(Key::DB_TIME_FORMAT);
        }
        return parent::toString($format);
    }

    /**
     * @return System_Time
     */
    public static function getInitTime() {
        return new self('00:00:00');
    }

}