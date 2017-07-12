<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 18/3/2017
 * Time: 09:45 AM
 */
class Config_Sudestnamsoftwarecom_Parameter extends Config_Parameter {

    function __construct() {
        parent::__construct();
        $this->parameter[K::DB] = array(
            K::hostname => 'localhost',
            K::database => 'nam_satellite_v1_sudest',
            K::username => 'nam_satellite',
            K::password => '4u7uzegep',
            K::timezone => 'Asia/Saigon',
            K::charset => 'utf8',
        );
        $this->parameter[K::NAM_API_URl] = 'http://tch1.ddns.net:8080';
        $this->parameter[K::SITE_CODE] = 'sudest.namsoftware.com';
        $this->parameter[K::COPYRIGHT] = '© Copyright 2006 - 2017 by sudest.namsoftware.com. All Rights Reserved';
        $this->parameter[K::BACKUP_PLANE] = 'BKP226172';
        $this->parameter[K::NO_DB] = true;
        $this->parameter[K::PLATFORM] = 'windows';
    }

}

