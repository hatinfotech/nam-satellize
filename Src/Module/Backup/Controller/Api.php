<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 24/5/2017
 * Time: 14:53
 */
class Backup_Controller_Api extends Controller_Api {

    function __construct($bootstrap) {
        parent::__construct($bootstrap);
    }

    public static function g($bootstrap) {
        return new self($bootstrap);
    }

    public function mainAction() {

    }

    protected function executeApi($get, $data, $file) {
        set_time_limit(0);
        $name = $data[K::name] ?: $get[K::name];
        $folder = $data[K::folder] ?: $get[K::folder];
        $backupFileName = $name . '_' . date('Y_m_d_H_i_s') . '.7z';
        $output = array();
        $return = null;
        $cmd = '"' . BASE_DIR . '/bin/7zr64.exe" -m0=Copy a -t7z "' . BASE_DIR . '/data/' . $backupFileName . '" "' . $folder . '"';
        exec($cmd, $output, $return);
        return array(
            'filename' => $backupFileName
        );
    }

    protected function cleanTmpBackupFileApi($get, $post) {
        $filename = $post['filename'] ?: $get['filename'];
        unlink(BASE_DIR . '/data/' . $filename);
        return true;
    }

} 