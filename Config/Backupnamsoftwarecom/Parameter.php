<?php


/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 18/3/2017
 * Time: 09:45 AM
 */
class Config_Backupnamsoftwarecom_Parameter extends Config_Parameter {

    function __construct() {
        parent::__construct();
        $this->parameter[K::DB] = array(
            K::hostname => 'localhost',
            K::database => 'nam_backup',
            K::username => 'nambackup',
            K::password => 'nambackup',
            K::timezone => 'Asia/Saigon',
            K::charset => 'utf8',
        );
        $this->parameter[K::SITE_CODE] = 'backup.namsoftware.com';
        $this->parameter[K::NAM_API_URl] = 'http://local.namsoftware.com';
    }

}






//

