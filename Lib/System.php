<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 24/5/2017
 * Time: 15:34
 */
class System {

    protected static $busErrors = array();
    protected static $log = array();

    public static function busAndLogError($errorMsg, $getFile = null, $getLine = null, $autoTranslate = true) {
        self::busError($errorMsg);
    }

    public static function getErrorBusLogs() {
        return self::$busErrors;
    }

    public static function getLogs() {
        return '';
    }

    public static function busError($message) {
        self::$busErrors[] = $message;
    }
}