<?php
/**
 * Created by PhpStorm.
 * User: hoangtriet
 * Date: 29/10/17
 * Time: 12:59 PM
 */

class Test_Controller_C1 extends Controller {

    function __construct(Bootstrap $bootstrap) {
        parent::__construct($bootstrap);
        $this->setWorkWithTemplate(false);
    }

    public static function g($bootstrap) {
        return new self($bootstrap);
    }

    public function noneUploadAction() {
        $source = $this->getBootstrap()->getRequestParams('src');
        $destination = $this->getBootstrap()->getRequestParams('dst');
        $ftpConn = ftp_connect('tch1.ddns.net');
        if (!ftp_login($ftpConn, 'backup', 'mtsg@513733')) {
            throw new Exception_Business('System could not login to ftp server');
        }

        if (!ftp_put($ftpConn, $destination, $source, FTP_BINARY, 1000000000)) {
            throw new Exception_Business('System could not upload file to ftp server');
        }

        echo "Upload complete";

        ftp_close($ftpConn);
    }

    public function uploadAction() {

        $source = $this->getBootstrap()->getRequestParams('src');
        $destination = $this->getBootstrap()->getRequestParams('dst');
        //        $position = $this->getBootstrap()->getRequestParams('pos');

        $ftpClient = new FTPClient('tch1.ddns.net');
        if (!$ftpClient->login('backup', 'mtsg@513733')) {
            throw new Exception_Business('Systen could not login to ftp server');
        }

        $position = $ftpClient->getFileSize($destination);

        if (!$ftpClient->upload($source, $destination, FTPClient::MODE_BINARY, $position > 0 ? $position : 0)) {
            throw new Exception_Business('System could not upload file to ftp server');
        }

        echo "Upload complete\n";

    }

    public function getRemoteFileSizeAction() {

        $file = $this->getBootstrap()->getRequestParams('file');

        $ftpClient = new FTPClient('tch1.ddns.net');
        if (!$ftpClient->login('backup', 'mtsg@513733')) {
            throw new Exception_Business('Systen could not login to ftp server');
        }

        $size = $ftpClient->getFileSize($file);
        echo "Size : $size \n";
    }


    public function getFileSizeAction() {
        echo Common::getFileSize($this->getBootstrap()->getRequestParams('file'));
    }

    public function checkProcessAction() {
        error_reporting(0);
        $pid = $this->getBootstrap()->getRequestParams('pid');
        $result = Common::checkProcessRunning($pid);
        echo "check pid => " . ($result ? 'true' : 'false') . "\n";
    }

    public function writePidToDiskAction() {
        file_put_contents(BASE_DIR . '/backup-run.pid', getmypid());
    }


}