<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 18/3/2017
 * Time: 09:45 AM
 */
class Config_Tvccmaytinhsaigonnet3720_Parameter extends Config_Parameter {

    function __construct() {
        parent::__construct();
        $this->parameter[K::DB] = array(
            K::hostname => 'localhost',
            K::database => 'nam_satellite_v1_tvfast',
            K::username => 'nam_satellite',
            K::password => '4u7uzegep',
            K::timezone => 'Asia/Saigon',
            K::charset => 'utf8',
        );
        $this->parameter[K::NAM_API_URl] = 'https://trans.namsoftware.com';
        $this->parameter[K::SITE_CODE] = 'vantoc.com';
        $this->parameter[K::COPYRIGHT] = '© Copyright 2006 - 2017 by vantoc.com. All Rights Reserved';
        $this->parameter[K::NO_DB] = true;
        $this->parameter[K::PLATFORM] = 'windows';
    }

}

