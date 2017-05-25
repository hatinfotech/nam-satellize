<?php

/**
 * Created by PhpStorm.
 * User: Hat
 * Date: 8/9/2015
 * Time: 7:38 AM
 */
class System_Network {

    /**
     * @param $host
     * @param $port
     * @param int $timeout
     * @return bool
     */
    public static function checkPort($host, $port, $timeout = 5) {
        flush();

        // TODO: deal with exceptions thrown by fsockopen
        $handle = fsockopen($host, $port, $errno, $errstr, $timeout);

        if ($handle) {
            fclose($handle);
            return true;
        }
    }

    /**
     * @param $host
     * @param $port
     * @param int $timeout
     * @return int
     */
    public static function checkUdpPort($host, $port, $timeout = 5) {
        $errno = 0;
        $errstr = "";
        flush();
        $host = "udp://" . $host;

        $handle = fsockopen($host, $port, $errno, $errstr, $timeout);

        if (!$handle) {
            System::error("$errno : $errstr <br/>", __FILE__, __LINE__);
        }

        // TODO: verify that socket_set_timeout() is required
        socket_set_timeout($handle, $timeout);
        $write = fwrite($handle, "\x00");
        if (!$write) {
            System::error("error writing to port: $port.", __FILE__, __LINE__);
            return false;
        }
        $startTime = time();
        $header = fread($handle, 1);
        $endTime = time();
        $timeDiff = $endTime - $startTime;

        if ($timeDiff > $timeout) {
            fclose($handle);
            return 0;
        } else {
            fclose($handle);
            return 1;
        }
    }
} 