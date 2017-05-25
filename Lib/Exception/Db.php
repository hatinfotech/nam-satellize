<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 29/12/2016
 * Time: 07:23 PM
 */
class Exception_Db extends Exception_Business {
    const DUPLICATE_EXCEPTION = 101;
    public static $errorCode = [
        self::DUPLICATE_EXCEPTION => 'Duplicate',
    ];
} 