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

    public function scanFileAction() {
        $list = $this->scanFile('D:\Soft', 24 * 60 * 60);
        Common::printArr($list);
    }

    protected function scanFile($folder, $newestTime = null) {
        $list = array();
        $currentDate = new DateTime();
        if ($handle = opendir($folder)) {
            //echo "Directory handle: $handle\n";
            //echo "Entries:\n";

            /* This is the WRONG way to loop over the directory. */
            while ($entry = readdir($handle)) {
                //echo "$entry\n";
                if ($entry != '.' && $entry != '..') {
                    if (is_dir($folder . '/' . $entry)) {
                        $list = array_merge($list, $this->scanFile($folder . '/' . $entry, $newestTime));
                    } else {
                        $created = filemtime($folder . '/' . $entry);
                        if (!$newestTime || $created >= $currentDate->getTimestamp() - $newestTime) {
                            $list[] = $folder . '/' . $entry;
                        }
                    }
                }
            }

            closedir($handle);
        }
        return $list;
    }

    protected function executeApi($get, $data, $file) {
        set_time_limit(0);
        $name = $data[K::name] ?: $get[K::name];
        $folder = $data[K::folder] ?: $get[K::folder];
        $newestTime = $data[K::newestTime] ?: $get[K::newestTime];
        $backupFileName = $name . '_' . date('Y_m_d_H_i_s') . '.7z';
        $output = array();
        $return = null;

        $listFile = null;
        $backupFile = BASE_DIR . '/data/' . $backupFileName;
        if ($newestTime) {
            $fileList = $this->scanFile($folder, $newestTime ? $newestTime * 60 : null);
            $listFile = BASE_DIR . '/tmp/bk' . $backupFileName . '.list';
            //echo "$listFile\n";
            file_put_contents($listFile, '' . "\n");
            foreach ($fileList as $file) {
                file_put_contents($listFile, '"' . $file . '"' . "\n", FILE_APPEND);
            }
            //$cmd = '"' . BASE_DIR . '/bin/7zr64.exe" -m0=Copy a -t7z "' . BASE_DIR . '/data/' . $backupFileName . '" -spf2 @"' . $listFile . '"';
            $cmd = '"' . BASE_DIR . '/bin/7zr64.exe" a -t7z "' . $backupFile . '" -spf2 @"' . $listFile . '"';
        } else {
            //$cmd = '"' . BASE_DIR . '/bin/7zr64.exe" -m0=Copy a -t7z "' . BASE_DIR . '/data/' . $backupFileName . '" "' . $folder . '"';
            $cmd = '"' . BASE_DIR . '/bin/7zr64.exe" a -t7z "' . $backupFile . '" "' . $folder . '"';
        }
        exec($cmd, $output, $return);

        if ($listFile) {
            unlink($listFile);
        }
        if (!file_exists($backupFile)) {
            System::busError(implode("\n", $output));
            throw new Exception_Business('Could not compress folder');
        }
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