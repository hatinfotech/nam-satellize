<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 30/5/2017
 * Time: 15:52
 */
class Backup_Controller_Client extends Controller {

    /**
     * @param Bootstrap $bootstrap
     */
    function __construct($bootstrap) {
        parent::__construct($bootstrap);
    }

    /**
     * @param $bootstrap
     * @return Backup_Controller_Client|Controller
     */
    public static function g($bootstrap) {
        return new self($bootstrap);
    }

    protected function getRemoteFileSize($remoteFile) {

    }

    /**
     * @param string $file
     * @param $ftpInfo
     * @return bool
     */
    protected function uploadFile($file, $ftpInfo) {

        $connId = null;
        try {
            echo "Input file : $file\n";
            $baseFilename = basename($file);

            $remotePath = $ftpInfo[K::path];
            $remoteFile = $baseFilename;
            echo "\$remote_file = $remoteFile\n";

            print_r($ftpInfo);

            // set up basic connection
            $connId = ftp_connect($ftpInfo[K::host], $ftpInfo[K::port] ?: 21);

            // login with username and password
            $login_result = ftp_login($connId, $ftpInfo[K::username], $ftpInfo[K::password]);
            ftp_pasv($connId, true);

            if (!$login_result) {
                System::busError('Ftp login fail');
                return false;
            }

            echo "\$login_result = $login_result\n";

            // upload a file
            $pathParts = explode('/', $remotePath);
            print_r($pathParts);
            $tmpPath = '';
            foreach ($pathParts as $pathPart) {
                if (trim($pathPart)) {
                    $tmpPath .= $pathPart . '/';
                    echo $tmpPath . "\n";
                    if (!ftp_mkdir($connId, $tmpPath)) {
                        System::busError('Ftp server was not create');
                    }
                }
            }


            echo "\$remote_path : $remotePath \n";
            $pos = 0;
            $try = 0;
            $maxTry = 10;
            $api = NaMApi::g();
            while (true) {
                $try++;
                //$pos = ftp_size($connId, $remotePath . '/' . $remoteFile);
                $getSizeResponse = $api->getBackupFileSize($remotePath . '/' . $remoteFile);
                $pos = $getSizeResponse[K::data];
                echo "Current remote size : $pos\n";
                $pos = $pos < 0 ? 0 : $pos;
                if (ftp_put($connId, $remotePath . '/' . $remoteFile, $file, FTP_BINARY, $pos)) {
                    break;
                }
                echo "Continue upload file (try $try/$maxTry)\n";
                if ($try >= $maxTry) {
                    throw new Exception_Business("There was a problem while uploading $file");
                }
            }


            // close the connection
            ftp_close($connId);
            return true;
        } catch (Exception $e) {
            if ($connId) {
                ftp_close($connId);
            }
            echo $e->getMessage();
            return false;
        }
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

    public function liveAction() {
        $this->setWorkWithTemplate(false);
        $api = NaMApi::g();
        $plane = $this->bootstrap->getRequestParams('plane');
        $api->updateLiveStatus($plane);
    }

    public function runAction() {
        set_time_limit(0);
        error_reporting(E_ALL);
        $this->setWorkWithTemplate(false);
        $zip = null;
        if (Config_Parameter::g(K::PLATFORM) == 'windows') {
            $zip = BASE_DIR . '/bin/' . ($this->bootstrap->getArchitecture() == 64 ? '7zr64.exe' : '7zr.exe');
        } elseif (Config_Parameter::g(K::PLATFORM) == 'linux') {
            $zip = '7za';
        }

        $plane = $this->bootstrap->getRequestParams('plane') ?: Config_Parameter::g(K::BACKUP_PLANE);


        // get backup info
        $api = NaMApi::g();

        $data = $api->getBackupPlane($plane);
        print_r($data);
        if (!$data || !$data['return']) {
            throw new Exception_Business("System could not get backup plane");
        }
        $plane = $data['data'];
        $ftpInfo = array(
            K::host => $plane['FtpHost'],
            K::port => $plane['FtpPort'],
            K::username => $plane['FtpUsername'],
            K::password => $plane['FtpPassword'],
            K::path => $plane['FtpPath'] . '/' . $plane['Code'],
        );

        //print_r($plane);
        $locations = $plane['locations'];
        $schedules = $plane['schedules'];


        echo "count : " . count($schedules) . "\n";

        // update schedule state => RUNNING, last running

        //echo count($locations)."\n";
        foreach ($locations as $location) {

            $name = $location[K::Name];
            $folder = $location[K::Folder];
            $newestTime = $location[K::NewestTime];
            $backupFileName = $name . '_' . date('Y_m_d_H_i_s') . '.7z';
            $output = array();
            $return = null;
            $listFile = null;
            $backupFile = BASE_DIR . '/data/' . $backupFileName;

            // -----Re upload------------------------------
            echo "Check for previous running state\n";
            $previousBackupFile = BASE_DIR . '/data/' . $location['LastRunningFile'];
            $ftpInfo[K::path] = trim($plane['FtpPath'] . '/' . $plane['Code'] . '/' . $location['Name'], '/');
            if ($location['LastRunningFile'] && file_exists($previousBackupFile)) {
                echo "\$previousBackupFile : $previousBackupFile\n";
                echo "Re upload previous backup file\n";
                $previousLocalFileSize = filesize($previousBackupFile);
                //$previousRemoteFileSize = $this->getRemoteFileSize($location['LastRunningFile'], $ftpInfo);
                $getSizeResponse = $api->getBackupFileSize($plane['Code'] . '/' . $location['Name'] . '/' . $location['LastRunningFile']);
                $previousRemoteFileSize = $getSizeResponse[K::data];
                echo "\$previousLocalFileSize($previousLocalFileSize)\n";
                echo "\$previousRemoteFileSize($previousRemoteFileSize)\n";
                if ($previousRemoteFileSize < $previousLocalFileSize) {
                    if (!$this->uploadFile($previousBackupFile, $ftpInfo)) {
                        System::busError('System could not upload backup file to ftp server');
                    } else {
                        // remove backup file on local
                        unlink($previousBackupFile);
                        // write backup history with remote filename
                        echo "write backup history with remote filename\n";
                        $api->writeBackupHistory($plane['Code'], $location['Name'], $previousBackupFile, 'SUCCESS');
                        $api->updateLocationLastRunningStateAsSuccess($location[K::Id], $location['LastRunningFile']);
                    }
                }
            }
            // -------------------------
            //exit;

            foreach ($schedules as $schedule) {
                echo "update schedule state => RUNNING, last running\n";
                $api->updateBackupScheduleToRunningState($schedule[K::Id]);


                echo "backup for location\n";
                print_r($location);

                echo "Update location backing up\n";
                $api->updateLocationBackingUp($schedule[K::Id], $location[K::Id], $backupFileName);


                if ($newestTime) {
                    $fileList = $this->scanFile($folder, $newestTime ? $newestTime * 60 : null);
                    $listFile = BASE_DIR . '/tmp/bk' . $backupFileName . '.list';
                    //echo "$listFile\n";
                    file_put_contents($listFile, '' . "\n");
                    foreach ($fileList as $file) {
                        file_put_contents($listFile, '"' . $file . '"' . "\n", FILE_APPEND);
                    }
                    $cmd = '"' . $zip . '" a -t7z "' . $backupFile . '" -spf2 @"' . $listFile . '"';
                } else {
                    $cmd = '"' . $zip . '" a -t7z "' . $backupFile . '" "' . $folder . '"';
                }
                echo "$cmd\n";
                exec($cmd, $output, $return);

                if ($listFile) {
                    unlink($listFile);
                }
                if (!file_exists($backupFile)) {
                    System::busError(implode("\n", $output));
                    throw new Exception_Business('Could not compress folder');
                }
                //return array(
                //    'filename' => $backupFileName
                //);

                $ftpInfo[K::path] = trim($plane['FtpPath'] . '/' . $plane['Code'] . '/' . $location['Name'], '/');

                // post file
                echo "post backup file to ftp server\n";
                if (!$this->uploadFile($backupFile, $ftpInfo)) {
                    System::busError('System could not upload backup file to ftp server');
                    echo "Write history for upload failed\n";
                    $api->updateLocationLastRunningStateAsFailed($location[K::Id], $backupFileName);
                } else {
                    // remove backup file on local
                    unlink($backupFile);
                    // write backup history with remote filename
                    echo "write backup history with remote filename\n";
                    $api->writeBackupHistory($plane['Code'], $location['Name'], $backupFileName, 'SUCCESS');
                    $api->updateLocationLastRunningStateAsSuccess($location[K::Id], $backupFileName);
                }
                // update schedule state => READY
                echo "update schedule state => READY\n";
                $api->updateBackupScheduleToReadyState($schedule[K::Id]);
            }

        }

        echo "backup complete\n";
        return true;


        ////$backupFileName = 'datafile.vmdk';
        ////$backupFileName = '26-05-2017.sql';
        ////$backupFile = BASE_DIR . '/data/' . 'datafile.vmdk';
        //$backupFile = "F:\MISASME.NET2012Backup.rar";
        //
        //$file = new SplFileObject($backupFile, 'rb');
        ////Common::printArr($file->getSize());
        //$headers = array();
        ////echo Config_Parameter::g(K::NAM_API_URl) . '/Backup/Api/request?command=transferFile';
        ////$contents = $this->uploadFile(Config_Parameter::g(K::NAM_API_URl) . '/Backup/Api/request?command=transferFile', 'POST', $file, $headers);
        ////$contents = $this->uploadFile2($file);
        //$contents = $this->uploadFile($backupFile, []);
        ////Common::printArr($contents);
        //$headers = $contents['header'];
        ////Common::printArr($headers);
        //if ($headers[0] == 'HTTP/1.1 200 OK') {
        //    print_r($contents['response']);
        //}
    }

} 