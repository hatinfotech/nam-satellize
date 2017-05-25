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
 * Description of Json
 *
 * @author Hat
 */
class System_Json {

    /**
     * Json encode
     * @param array|ArrayObject $value
     * @return string
     */
    public static function encode($value) {
        return json_encode($value);
    }

    /**
     * Json decode
     * @param string $json
     * @return array
     */
    public static function decode($json, $option = true) {
        return json_decode($json, $option);
    }

    /**
     * @param $json
     * @return mixed|string
     */
    public static function sPrint($json) {
        if (is_string($json)) {
            $json = self::decode($json);
        }
        return json_encode($json, JSON_PRETTY_PRINT);
    }

}
