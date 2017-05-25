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
 * Description of Security
 *
 * @author Hat
 */
class System_Security {

    /**
     * Security hash key algorithm
     * @param string $key
     * @return string
     */
    public static function hashTransferKey($key) {
        return md5($key . Config_Parameter::getInstance()->get(Key::SECURITY_SALT));
    }

    /**
     * @param $key
     * @param $hash
     * @return bool
     */
    public static function checkTransferKey($key, $hash) {
        if (self::hashTransferKey($key) == $hash) {
            return true;
        }
        return false;
    }

    /**
     * 
     * @param string $timeStamp
     * @param string $stokenKey
     * @return boolean
     */
    public static function checkStokenKey($timeStamp, $stokenKey) {
        $time = System_Date::getInstance()->getTimestamp();
        if ($time - $timeStamp > Config_Parameter::getInstance()->get(Key::STOKEN_TIMEOUT)) {
            System::error('Timeout');
            return false;
        }
        if (md5($timeStamp . Config_Parameter::getInstance()->get(Key::SECURITY_SALT)) != $stokenKey) {
            System::error('stoken key not match');
            return false;
        }
        return true;
    }

    /**
     * @param $password
     * @return string
     */
    public static function hashPassword($password) {
        return sha1(md5($password . Config_Parameter::getInstance()->get(Key::SECURITY_SALT)));
    }

}
