<?php


/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 18/3/2017
 * Time: 09:45 AM
 */
class Config_Vitinhsmyoucom_Parameter extends Config_Parameter {

    function __construct() {
        parent::__construct();
        $this->parameter[K::NAM_API_URl] = 'https://trans.namsoftware.com';
        $this->parameter[K::DB] = array(
            K::hostname => 'localhost',
            K::database => 'vitinhsm_db',
            K::username => 'vitinhsm',
            K::password => 'date7uzuj',
            K::timezone => 'Asia/Saigon',
            K::charset => 'utf8',
        );
        $this->parameter[K::SITE_CODE] = 'vitinhsmyou.com';
        $this->parameter[K::NAM_API_URl] = 'http://trans.namsoftware.com';
    }

}






//

