<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 18/3/2017
 * Time: 09:45 AM
 */
class Config_Devshippersaigoncom_Parameter extends Config_Parameter {

    function __construct() {
        parent::__construct();
        $this->parameter[K::DB] = array(
            K::hostname => 'localhost',
            K::database => 'shippers_db',
            K::username => 'shippers',
            K::password => '6a6e3u8yn',
            K::timezone => 'Asia/Saigon',
            K::charset => 'utf8',
        );
        $this->parameter[K::NAM_API_URl] = 'https://local.namsoftware.com';
        $this->parameter[K::SITE_CODE] = 'dev.shippersaigon.com';
        $this->parameter[K::PLATFORM] = 'windows';
    }

}

