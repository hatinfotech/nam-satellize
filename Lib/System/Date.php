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
 * Description of Date
 *
 * @author Hat
 */
class System_Date extends System_DataType {

    /**
     * Defined constant
     */
    const DATE_PARAM = 'date';
    /**
     *
     */
    const FORMAT_PARAM = 'format';
    /**
     *
     */
    const MIN_DATETIME = '1970-01-02 00:00:00';
    /**
     * Defined properties
     */

    /**
     * Contain DateTime object of php platform
     * @var DateTime
     */
    protected $date = null;
    /**
     * @var string
     */
    protected $format;

    /**
     * Initial
     */
    protected function init() {
        $this->format = Config_Parameter::g(K::DB_DATETIME_FORMAT);
    }

    /**
     * @param null $date
     * @param null $format
     */
    public function __construct($date = null, $format = null) {

        //Initial
        $this->init();

        if ($format != null) {
            $this->format = $format;
        }

        if ($date instanceof DateTime) {
            $this->date = $date;
        } elseif (is_numeric($date)) {
            $this->date = new DateTime("@$date");
        } elseif ($date != null) {
            $this->date = date_create_from_format($this->format, $date);
        } else {
            $this->date = new DateTime();
        }
    }

    /**
     * @param null $date
     * @param null $format
     * @return System_Date
     */
    public static function getInstance($date = null, $format = null) {
        return new self($date, $format);
    }

    /**
     * @return DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return $this
     */
    public function setDate(DateTime $date) {
        $this->date = $date;
        return $this;
    }

    /**
     * Get current date time by format
     * @param string $format
     * @return string
     */
    public static function getCurrDate($format = null) {
        return self::getInstance(NULL, $format)->toString();
    }

    /**
     * Add date to this date
     * @param DateInterval $dateInterval
     * @return $this
     */
    public function addDate($dateInterval) {
        //        $this->date->add($dateInterval);
        $this->date = new DateTime(date('Y-m-d H:i:s', $this->getTimestamp() + $dateInterval));
        return $this;
    }

    /**
     * Sub date
     * @param DateInterval $dateInterval
     * @return $this
     */
    public function subDate($dateInterval) {
        if ($dateInterval instanceof System_Date) {
            $dateInterval = $dateInterval->getTimestamp();
        }
        $this->date = new DateTime(date('Y-m-d H:i:s', $this->getTimestamp() - $dateInterval));
        return $this;
    }

    /**
     * Get previous month
     * @param $interval
     * @return System_Date
     */
    public static function getPreviousMonth($interval = 1) {

        $yearInterval = (int)($interval / 12);
        $monthInterval = $interval % 12;

        $date = self::getInstance();
        $month = $date->getMonth() - $monthInterval <= 0 ? (12 - $monthInterval + 1) : ($date->getMonth() - $monthInterval);
        $year = ($date->getMonth() - $monthInterval <= 0 ? ($date->getYear() - 1) : $date->getYear()) - $yearInterval;
        $day = $date->getDay() > self::getNumOfDayInMonth($month, $year) ? self::getNumOfDayInMonth($month, $year) : $date->getDay();
        return self::getInstance(sprintf("%'0" . 4 . "d", $year) . '-' . sprintf("%'0" . 2 . "d", $month) . '-' . sprintf("%'0" . 2 . "d", $day) . ' ' . sprintf("%'0" . 2 . "d", $date->getHour()) . ':' . sprintf("%'0" . 2 . "d", $date->getMin()) . ':' . sprintf("%'0" . 2 . "d", $date->getSecond()));
    }

    /**
     * Get next month
     * @param $interval
     * @return System_Date
     */
    public static function getNextMonth($interval = 1) {

        $yearInterval = (int)($interval / 12);
        $monthInterval = $interval % 12;

        $date = self::getInstance();
        $month = $date->getMonth() + $monthInterval > 12 ? ($date->getMonth() + $monthInterval - 12) : ($date->getMonth() + $monthInterval);
        $year = ($date->getMonth() + $monthInterval > 12 ? ($date->getYear() + 1) : $date->getYear()) + $yearInterval;
        $day = $date->getDay() > self::getNumOfDayInMonth($month, $year) ? self::getNumOfDayInMonth($month, $year) : $date->getDay();
        return self::getInstance($year . '-' . $month . '-' . $day . ' ' . $date->getHour() . ':' . $date->getMin() . ':' . $date->getSecond());
    }

    /**
     * @return System_Date
     */
    public function getBeginOfMonth() {
        return new self($this->toString('Y-m') . '-01 00:00:00'); //Todo : Fix for time set to 00:00:00
    }

    /**
     * @return System_Date
     */
    public function getEndOfMonth() {
        return new self($this->toString('Y-m') . '-' . $this->getMaxDayInMonth() . ' 23:59:59');
    }

    /**
     * @param null $format
     * @return bool|string
     */
    public function toString($format = null) {
        if (!$this->date) {
            return false;
        }
        if ($format != null) {
            return $this->date->format($format);
        }
        if ($this->format == null) {
            return $this->date->format(Config_Parameter::getInstance()->get(Key::DB_DATETIME_FORMAT));
        }
        return $this->date->format($this->format);
    }

    /**
     * @return int|string
     */
    public function getTimestamp() {
//        debug_print_backtrace();
        return method_exists('DateTime', 'getTimestamp') ? ($this->date ? $this->date->getTimestamp() : NULL) : ($this->date ? $this->date->format('U') : NULL);
    }

    /**
     * @param $strIntervalTime
     * @return int
     */
    public static function getTimeStampInterval($strIntervalTime) {
        return strtotime(date('Y-m-d H:i:s') . '+' . $strIntervalTime . '') - time();
    }

    /**
     * @return bool
     */
    public function getDay() {
        if ($this->date) {
            return (int)($this->date->format('d'));
        }
        return false;
    }

    /**
     * @return bool
     */
    public function getMonth() {
        if ($this->date) {
            return (int)($this->date->format('m'));
        }
        return false;
    }

    /**
     * @return bool
     */
    public function getYear() {
        if ($this->date) {
            return (int)($this->date->format('Y'));
        }
        return false;
    }

    /**
     * @return bool|int
     */
    public function getHour() {
        if ($this->date) {
            return (int)($this->date->format('H'));
        }
        return false;
    }

    /**
     * @return bool
     */
    public function getMin() {
        if ($this->date) {
            return (int)($this->date->format('i'));
        }
        return false;
    }

    /**
     * @return bool
     */
    public function getSecond() {
        if ($this->date) {
            return (int)($this->date->format('s'));
        }
        return false;
    }

    /**
     * @return System_Date|System_Time
     */
    public function getTime() {
        return System_Time::getInstance($this->toString(Config_Parameter::getInstance()->get(Key::DB_TIME_FORMAT)), Config_Parameter::getInstance()->get(Key::DB_TIME_FORMAT));
    }

    /**
     * @return System_Date
     */
    public static function getMinDate() {
        return System_Date::getInstance(self::MIN_DATETIME, Config_Parameter::getInstance()->get(Key::DB_DATETIME_FORMAT));
    }

    /**
     * @return System_Date
     */
    public function cloneMe() {
        $cloneObj = clone $this;
        $cloneObj->setDate(clone $this->getDate());
        return $cloneObj;
    }

    /**
     * @param $month
     * @param $year
     * @return bool|int
     */
    public static function getNumOfDayInMonth($month, $year) {
        if (!$month) {
            $month = System_Date::getInstance()->toString('m');
        }
        if (!$year) {
            $year = System_Date::getInstance()->toString('Y');
        }
        switch ($month) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                return 31;
            case 4:
            case 6:
            case 9:
            case 11:
                return 30;
            case 2:
                return $year % 4 == 0 ? 29 : 28;
        }
        return false;
    }

    /**
     * @return bool|int
     */
    public function getMaxDayInMonth() {
        return self::getNumOfDayInMonth($this->getMonth(), $this->getYear());
    }

    /**
     * @param $month
     * @param $year
     * @return System_Date
     */
    public static function makeFirstDateInMonth($month, $year) {
        $currentMonth = System_Date::getInstance()->toString(($year ?: 'Y') . '-' . ($month ?: 'm'));
        return new self($currentMonth . '-01', 'Y-m-d');
    }

    /**
     * @param $month
     * @param $year
     * @return System_Date
     */
    public static function makeEndDateInMonth($month, $year) {
        $currentMonth = System_Date::getInstance()->toString(($year ?: 'Y') . '-' . ($month ?: 'm'));
        return new self($currentMonth . '-' . self::getNumOfDayInMonth($month, $year), 'Y-m-d');
    }


    /**
     * Returns the difference between two DateTime objects represented as a DateInterval.
     * @param DateTime|System_Date $date
     * @return bool|DateInterval
     */
    public function diff($date) {
        if ($date instanceof System_Date) {
            $date = $date->getDate();
        }
        return $this->getDate()->diff($date);
    }

    /**
     * @param $month
     * @param $year
     * @return int
     */
    public static function getNumOfDayInMonth_($month, $year) {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    /**
     * @return bool|string
     */
    function __toString() {
        return $this->toString();
    }

    /**
     * @return int
     */
    public function getNumOfSunday() {
        $endDay = $this->getMaxDayInMonth();
        $numOfSunday = 0;
        for ($i = 0; $i <= $endDay; $i++) {
            $d = getdate(strtotime($this->getYear() . '-' . $this->getMonth() . '-' . $i));
            if ($d['wday'] === 0) {
                $numOfSunday++;
            }
        }
        return $numOfSunday;
    }

    /**
     * @param int $mode
     * @return mixed
     */
    public function getDayOfWeek($mode = CAL_DOW_DAYNO) {
        return jddayofweek(cal_to_jd(CAL_GREGORIAN, $this->getMonth(), $this->getDay(), $this->getYear()), $mode);
    }

    /**
     * @return $this
     */
    public function setTimeAsBeginDay() {
        $this->date->setTime(0, 0, 0);
        return $this;
    }

    /**
     * @return $this
     */
    public function setTimeAsEndDay() {
        $this->date->setTime(23, 59, 59);
        return $this;
    }

    /**
     * Static convert by format
     * @param $value
     * @param $fromFormat
     * @param $toFormat
     * @return mixed
     */
    public function convertByFormat($value, $fromFormat, $toFormat) {

    }
}
