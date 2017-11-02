<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 30/5/2017
 * Time: 15:52
 */
class Backup_Controller_Client extends Controller implements FTPClient_Context {

    /**
     * @var FTPClient
     */
    protected $ftpConnection;

    /**
     * @param Bootstrap $bootstrap
     */
    function __construct($bootstrap) {
        parent::__construct($bootstrap);
        $this->setWorkWithTemplate(false);
    }

    /**
     * @param $bootstrap
     * @return Backup_Controller_Client|Controller
     */
    public static function g($bootstrap) {
        return new self($bootstrap);
    }

    protected function getRemoteFileSize($remoteFile, $ftpCon = null, $ftpInfo = null) {
        $connection = null;
        if (!$ftpCon) {
            $connection = ftp_connect($ftpInfo[K::host], $ftpInfo[K::port] ?: 21);
            $login_result = ftp_login($connection, $ftpInfo[K::username], $ftpInfo[K::password]);
            if (!$login_result) {
                throw new Exception_Business('System could not login to server');
            }
        } else {
            $connection = $ftpCon;
        }
        $size = ftp_size($connection, $remoteFile);
        if (!$ftpCon) {
            ftp_close($connection);
        }
        return $size;
    }

    public function getRemoteFileSizeAction() {
        $file = $this->getBootstrap()->getRequestParams('file');
        $connId = ftp_connect('tch1.ddns.net', 21);
        $login_result = ftp_login($connId, 'backup', 'mtsg@513733');
        ftp_pasv($connId, true);
        if (!$login_result) {
            echo('Ftp login fail');
            return false;
        }

        echo "Remote file size : ";
        echo $this->getRemoteFileSize($file, $connId);
        echo "\n";

        ftp_close($connId);
    }

    /**
     * @param string $file
     * @param $ftpInfo
     * @param null $ftpConn
     * @return bool
     */
    protected function uploadFile($file, $ftpInfo, $ftpConn = null) {

        $connId = null;
        try {
            echo "Input file : $file\n";
            $baseFilename = basename($file);

            $remotePath = $ftpInfo[K::path];
            $remoteFile = $baseFilename;
            echo "\$remote_file = $remoteFile\n";

            print_r($ftpInfo);

            // set up basic connection
            if (!$ftpConn) {
                $connId = ftp_connect($ftpInfo[K::host], $ftpInfo[K::port] ?: 21);
                ftp_set_option($connId, FTP_AUTOSEEK, true);
                // login with username and password
                $login_result = ftp_login($connId, $ftpInfo[K::username], $ftpInfo[K::password]);
                ftp_pasv($connId, true);
                echo "\$login_result = $login_result\n";
                if (!$login_result) {
                    System::busError('Ftp login fail');
                    return false;
                }
            } else {
                $connId = $ftpConn;
            }

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

            $remoteFilePath = $remotePath . '/' . $remoteFile;

            echo "\$remote_path : $remotePath \n";
            echo "\$remote_file_path : $remoteFilePath \n";
            $pos = 0;
            $try = 0;
            $maxTry = 10;
            $api = NaMApi::g();
            //            $stream = fopen($file, 'r');
            //            while (true) {
            //                $try++;
            //$pos = ftp_size($connId, $remotePath . '/' . $remoteFile);
            $pos = $this->getRemoteFileSize($remoteFilePath, $connId);
            echo "Current remote size : $pos\n";
            $pos = $pos < 0 ? 0 : $pos;
            if (!ftp_put($connId, $remoteFilePath, $file, FTP_BINARY, $pos)) {
                throw new Exception_Business("There was a problem while uploading $file");
            }
            ftp_chmod($connId, 777, $remoteFilePath);
            //                    ftp_close($connId);
            //                    sleep(15);
            //                    $connId = ftp_connect($ftpInfo[K::host], $ftpInfo[K::port] ?: 21);
            //                    ftp_set_option ($connId, FTP_AUTOSEEK, true);
            //
            // login with username and password
            //                    $login_result = ftp_login($connId, $ftpInfo[K::username], $ftpInfo[K::password]);
            //                    ftp_pasv($connId, true);
            //                    echo "\$login_result = $login_result\n";
            //                    if (!$login_result) {
            //                        System::busError('Ftp login fail on retry connect');
            //                        return false;
            //                    }
            //                    fclose($stream);
            //                    break;
            //            }
            //            echo "Continue upload file (try $try/$maxTry)\n";
            //            if ($try >= $maxTry) {
            //                //                    fclose($stream);
            //                throw new Exception_Business("There was a problem while uploading $file");
            //            }
            //            }
            //            fclose($stream);

            // close the connection
            if (!$ftpConn) {
                ftp_close($connId);
            }
            return true;
        } catch (Exception $e) {
            if (!$ftpConn) {
                if ($connId) {
                    ftp_close($connId);
                }
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
        //        echo "git fetch --all && git reset --hard origin/master\n";
        //        echo exec("git fetch --all && git reset --hard origin/master") . "\n";
        sleep(15);
        $this->setWorkWithTemplate(false);
        $api = NaMApi::g();
        $plane = $this->bootstrap->getRequestParams('plane');
        $result = $api->updateLiveStatus($plane, Common::checkProcessRunning(file_get_contents(BASE_DIR . '/backup-run.pid')));
        print_r($result);
    }

    protected $log = '';

    protected function writeLog($log) {
        $tmp = '';
        if (is_array($log)) {
            print_r($log);
            echo "\n";
            $tmp = $this->log .= json_encode($log, JSON_PRETTY_PRINT) . "\n";
        } else {
            echo "$log\n";
            $tmp = $this->log .= "$log\n";
        }
        error_log($tmp);
    }

    /**
     * Run backup
     * @param bool $immediate
     * @return bool
     */
    public function runAction($immediate = false) {
        $ftpConn = null;
        set_time_limit(0);
        error_reporting(E_ALL);
        $plane = $this->bootstrap->getRequestParams('plane') ?: Config_Parameter::g(K::BACKUP_PLANE);
        try {
            $this->writeLog("################ BACKUP FOR PLANE $plane ##################");
            $this->setWorkWithTemplate(false);
            $zip = null;
            if (Config_Parameter::g(K::PLATFORM) == 'windows') {
                $zip = BASE_DIR . '/bin/' . ($this->bootstrap->getArchitecture() == 64 ? '7zr64.exe' : '7zr.exe');
            } elseif (Config_Parameter::g(K::PLATFORM) == 'linux') {
                $zip = '7za';
            }


            // Get backup info
            $api = NaMApi::g();

            $data = $api->getBackupPlane($plane);
            $this->writeLog($data);
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

            // Create ftp connection
            $this->ftpConnection = new FTPClient($ftpInfo[K::host], $ftpInfo[K::port], FTPClient::TRANSFER_MODE_PASSIVE);
            if (!$this->ftpConnection->login($ftpInfo[K::username], $ftpInfo[K::password])) {
                throw new Exception_Business('System could not login ftp server');
            }
            //            $ftpConn = ftp_connect($ftpInfo[K::host], $ftpInfo[K::port] ?: 21);
            //            $login_result = ftp_login($ftpConn, $ftpInfo[K::username], $ftpInfo[K::password]);
            //            ftp_pasv($ftpConn, true);
            //            ftp_set_option($ftpConn, FTP_AUTOSEEK, true);

            $locations = $plane['locations'];
            $schedules = $plane['schedules'];

            $this->writeLog("count : " . count($schedules));

            // Continue upload previous backup file
            foreach ($locations as $location) {


                $name = $location[K::Name];
                $folder = $location[K::Folder];
                $newestTime = $location[K::NewestTime];
                $backupFileName = $name . '_' . date('Y_m_d_H_i_s') . '.7z';
                $output = array();
                $return = null;
                $listFile = null;
                $backupFile = BASE_DIR . '/data/' . $backupFileName;

                try {
                    // -----Re upload------------------------------
                    $this->writeLog("Check for previous running state");
                    $previousBackupFile = BASE_DIR . '/data/' . $location['LastRunningFile'];
                    $ftpInfo[K::path] = trim($plane['FtpPath'] . '/' . $plane['Code'] . '/' . $location['Name'], '/');

                    //                    if (!$login_result) {
                    //                        throw new Exception_Business('System could not login to server');
                    //                    }
                    $this->writeLog("Check and reupload backup file of location");
                    $this->writeLog($location);
                    echo "\n";
                    if ($location['LastRunningState'] == 'UPLOADING' && $location['LastRunningFile'] && file_exists($previousBackupFile)) {
                        $this->writeLog("\$previousBackupFile : $previousBackupFile");
                        $this->writeLog("Re upload previous backup file");
                        $previousLocalFileSize = filesize($previousBackupFile);
                        //$previousRemoteFileSize = $this->getRemoteFileSize($location['LastRunningFile'], $ftpInfo);
                        $remoteFilePath = $plane['Code'] . '/' . $location['Name'] . '/' . $location['LastRunningFile'];
                        $previousRemoteFileSize = $this->ftpConnection->getFileSize($remoteFilePath);
                        //                        $previousRemoteFileSize = $getSizeResponse[K::data];
                        $this->writeLog("local : $previousBackupFile ($previousLocalFileSize)");
                        $this->writeLog("remote : $remoteFilePath ($previousRemoteFileSize)");
                        $api->writeBackupHistory($plane['Code'], $location['Name'], $location['LastRunningFile'], 'REUPLOADING', "Reuploading backup file\n" . $this->log);
                        if ($previousRemoteFileSize < $previousLocalFileSize) {
                            if (!$this->ftpConnection->upload($previousBackupFile, $remoteFilePath, FTPClient::MODE_BINARY, $previousRemoteFileSize)) {
                                throw new Exception_Business('System could not continue upload backup file to ftp server');
                            }
                            // remove backup file on local
                            if (!unlink($previousBackupFile)) {
                                $this->writeLog("System could not remove previous backup file");
                            }
                            // write backup history with remote filename
                            $this->writeLog("write backup history with remote filename");
                            $api->writeBackupHistory($plane['Code'], $location['Name'], $location['LastRunningFile'], 'REUPLOADSUCCESSFUL', "Reupload backup file successful\n" . $this->log);
                            $api->updateLocationLastRunningStateAsSuccess($location[K::Id], $location['LastRunningFile']);

                        }
                    } else {
                        //                        $log .= ob_get_clean();
                        //                        ob_start();
                        //                        $api->writeBackupHistory($plane['Code'], $location['Name'], $location['LastRunningFile'], 'CHECKREUPLOAD', "No file for reupload\n" . $log);
                    }
                } catch (Exception $e) {
                    $api->writeBackupHistory($plane['Code'], $location['Name'], $location['LastRunningFile'], 'REUPLOADFAILED', "Reupload backup file was failed\n" . $this->log);
                    throw new Exception_Business($e->getMessage(), $e->getCode(), $e);
                }


                if ($immediate) {
                    $this->writeLog("Run backup immediate");
                    $schedules[] = false;
                }

                // Backup file by schedule
                foreach ($schedules as $schedule) {

                    //                    if (count($schedules) > 0) {
                    // Store pid
                    $curPid = file_get_contents(BASE_DIR . '/backup-run.pid');
                    if ($curPid && Common::checkProcessRunning($curPid)) {
                        $this->writeLog("Previous process was ran, ski for wait");
                        if ($schedule) $api->updateBackupScheduleState($schedule[K::Id], 'WAITING');
                        break;
                    }
                    file_put_contents(BASE_DIR . '/backup-run.pid', getmypid());
                    //                    }

                    try {
                        $this->writeLog("update schedule state => RUNNING, last running");
                        if ($schedule) $api->updateBackupScheduleToRunningState($schedule[K::Id]);


                        $this->writeLog("Backup for location");
                        $this->writeLog($location);

                        $this->writeLog("Update location backing up");
                        if ($schedule) $api->updateLocationBackingUp($schedule[K::Id], $location[K::Id], $backupFileName);

                        // Execute pre command
                        if ($location[K::PreCommand]) {
                            $this->writeLog("Pre Command execute :");

                            $api->writeBackupHistory($plane['Code'], $location['Name'], $backupFileName, 'PRECOMMAND', "Execute pre command\n" . $this->log);

                            $this->writeLog($location[K::PreCommand]);
                            $output = array();
                            $return = NULL;
                            exec($location[K::PreCommand], $output, $return);

                            $this->writeLog("Return : ");
                            $this->writeLog($return);
                            $this->writeLog("Output : ");
                            $this->writeLog($output);

                            if ($return > 0) { // skip check
                                throw new Exception_Business('Execute pre command result fail');
                            }
                        }

                        // Compress backup files
                        echo "Compress target folder : \n";
                        $api->writeBackupHistory($plane['Code'], $location['Name'], $backupFileName, 'COMPRESS', "Compress target folder\n" . $this->log);

                        if ($newestTime) {
                            $fileList = $this->scanFile($folder, $newestTime ? $newestTime * 60 : null);
                            $listFile = BASE_DIR . '/tmp/bk' . $backupFileName . '.list';
                            file_put_contents($listFile, '' . "\n");
                            foreach ($fileList as $file) {
                                file_put_contents($listFile, '"' . $file . '"' . "\n", FILE_APPEND);
                            }
                            $cmd = '"' . $zip . '" a -t7z "' . $backupFile . '" -spf2 @"' . $listFile . '"';
                        } else {
                            $cmd = '"' . $zip . '" a -t7z "' . $backupFile . '" "' . $folder . '"';
                        }

                        $this->writeLog($cmd);
                        $output = array();
                        $return = NULL;
                        exec($cmd, $output, $return);

                        $this->writeLog("Return");
                        $this->writeLog($return);
                        $this->writeLog("Output");
                        $this->writeLog($output);

                        if ($listFile) {
                            unlink($listFile);
                        }

                        // Check for compress (u2)
                        if (false && $return > 0) {//Skip compress checking
                            $this->writeLog("Compress file fail");
                            unlink($backupFile);
                            throw new Exception_Business('Compress backup files was result failed');
                        }

                        // Check archive exists
                        if (!file_exists($backupFile)) {
                            $this->writeLog("Backup file was not exists");
                            throw new Exception_Business('Could not compress folder');
                        }

                        // Test archive
                        $this->writeLog("Test archive :");
                        $api->writeBackupHistory($plane['Code'], $location['Name'], $backupFileName, 'TESTARCHIVE', "Test archive after compress\n" . $this->log);
                        $output = array();
                        $return = NULL;
                        exec('7za t "' . $backupFile . '"', $output, $return);

                        $this->writeLog("Return");
                        $this->writeLog($return);
                        $this->writeLog("Output\n");
                        $this->writeLog($output);
                        if ($return == 2) {
                            unlink($backupFile);
                            throw new Exception_Business('Check for archive was result failed');
                        }

                        $ftpInfo[K::path] = trim($plane['FtpPath'] . '/' . $plane['Code'] . '/' . $location['Name'], '/');
                        $remoteFilePath = trim($plane['FtpPath'] . '/' . $plane['Code'] . '/' . $location['Name'] . '/' . $backupFileName, '/');

                        // Upload file
                        $this->writeLog("Post backup file to ftp server");
                        $this->writeLog("Set location last running => UPLOADING");
                        $api->writeBackupHistory($plane['Code'], $location['Name'], $backupFileName, 'UPLOADING', "Start upload backup file\n" . $this->log);
                        $api->updateLocationLastRunningState($location[K::Id], $backupFileName, 'UPLOADING');
                        // Update schedule state => READY
                        $this->writeLog("update schedule state => READY");
                        if ($schedule) $api->updateBackupScheduleToReadyState($schedule[K::Id]);
                        $api->writeBackupHistory($plane['Code'], $location['Name'], $backupFileName, 'SUCCESS', "Backup complete and file is now uploading to server\n" . $this->log);

                        try {
                            if (!$this->ftpConnection->upload($backupFile, $remoteFilePath, FTPClient::MODE_BINARY)) {
                                throw new Exception_Business('Upload backup file fail');
                            } else {
                                // Remove backup file on local
                                $this->writeLog("Remove backup file");
                                unlink($backupFile);
                                $api->writeBackupHistory($plane['Code'], $location['Name'], $backupFileName, 'SUCCESS', "BACKUP COMPLETE SUCCESSFUL\n" . $this->log);
                                // Write backup history with remote filename
                                $api->updateLocationLastRunningStateAsSuccess($location[K::Id], $backupFileName);
                            }
                        } catch (Exception $e) {
                            $this->writeLog("System could not upload backup file to ftp server, next fetch this backup file auto continue upload!");
                            $this->writeLog((string)$e);
                            $api->writeBackupHistory($plane['Code'], $location['Name'], $backupFileName, 'UPLOADFAILED', "Backup complete but backup file not upload to server now, \nupload process will be continue at next fetch\n" . $this->log);
                        }


                        $this->writeLog("Write backup history with remote filename");
                        $this->writeLog("BACKUP COMPLETE SUCCESSFUL");

                    } catch (Exception $e) {
                        $this->writeLog((string)$e);
                        // Set job fail on archive result fail
                        $this->writeLog("update location last state => FAILED");
                        $api->updateLocationLastRunningStateAsFailed($location[K::Id], $backupFileName);
                        $this->writeLog("write backup history => FAILED");
                        if ($schedule) $api->updateBackupScheduleToFailState($schedule[K::Id]);
                        $api->writeBackupHistory($plane['Code'], $location['Name'], $backupFileName, 'FAILED', $e->getMessage() . "\n" . $this->log . "\nTrace:\n" . ($e->getTraceAsString()));
                        throw new Exception_Business($e->getMessage(), $e->getCode(), $e);
                    }
                }

            }

            $this->ftpConnection->disconnect();

            $this->writeLog("=================== BACKUP FOR PLANE $plane SUCCESSFUL =========================");
            return true;
        } catch (Exception $e) {
            if ($this->ftpConnection) {
                $this->ftpConnection->disconnect();
            }
            echo $e;
        }
        $this->writeLog("=================== BACKUP FOR PLANE $plane FAILED =========================");
        return false;
    }

    public function runImmediateAction() {
        return $this->runAction(true);
    }

    public function testAction() {
        $this->setWorkWithTemplate(false);
        $connId = ftp_connect('tch1.ddns.net', 21);

        $login_result = ftp_login($connId, 'backup', 'mtsg@513733');
        ftp_pasv($connId, true);
        $remoteFile = 'BKP106177/Sunnet/Sunnet_2017_06_20_14_04_22.7z';
        var_dump(ftp_chmod($connId, 777, $remoteFile));
        ftp_close($connId);
    }

    public function onUploadProcess($ftpClient, $amountUploaded, $log) {
        $this->writeLog($log);
    }
}