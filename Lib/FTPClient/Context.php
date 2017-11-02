<?php
/**
 * Created by PhpStorm.
 * User: hoangtriet
 * Date: 3/11/17
 * Time: 1:23 AM
 */

interface FTPClient_Context {
    public function onUploadProcess($ftpClient, $amountUploaded, $log);
}