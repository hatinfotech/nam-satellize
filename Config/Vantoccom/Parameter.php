<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 18/3/2017
 * Time: 09:45 AM
 */
class Config_Vantoccom_Parameter extends Config_Parameter {

    function __construct() {
        parent::__construct();
        $this->parameter[K::DB] = array(
            K::hostname => 'localhost',
            K::database => 'vantocco_db',
            K::username => 'vantocco',
            K::password => '4u7uzegep',
            K::timezone => 'Asia/Saigon',
            K::charset => 'utf8',
        );
        $this->parameter[K::NAM_API_URl] = 'https://trans.namsoftware.com';
        $this->parameter[K::SITE_CODE] = 'vantoc.com';
        $this->parameter[K::COPYRIGHT] = '© Copyright 2006 - 2017 by vantoc.com. All Rights Reserved';
    }

}

