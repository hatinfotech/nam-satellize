<?php

/*
 * Copyright (C) 2013-2014 Hat <hat@maytinhsaigon.com>
 * 
 * This file is part of MTSG OPEN FRAMEWORK.
 * 
 * MTSG OPEN FRAMEWORK can not be copied and/or distributed 
 * without the express permission of MTSG
 */

/**
 * Description of System_Curl
 *
 * @author hat
 */
class System_Curl {

    /**
     * @var string
     */
    protected $_useragent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1';
    /**
     * @var string
     */
    protected $_url;
    /**
     * @var bool
     */
    protected $_followlocation;
    /**
     * @var int
     */
    protected $_timeout;
    /**
     * @var int
     */
    protected $_maxRedirects;
    /**
     * @var string
     */
    protected $_cookieFileLocation = './cookie.txt';
    /**
     * @var int
     */
    protected $_post;
    /**
     * @var string
     */
    protected $_postFields;
    /**
     * @var string
     */
    protected $_referer = "http://www.google.com";
    /**
     * @var
     */
    protected $_session;
    /**
     * @var string
     */
    protected $_webpage;
    /**
     * @var bool
     */
    protected $_includeHeader;
    /**
     * @var bool
     */
    protected $_noBody;
    /**
     * @var int
     */
    protected $_status;
    /**
     * @var bool
     */
    protected $_binaryTransfer;
    /**
     * @var int
     */
    public $authentication = 0;
    /**
     * @var string
     */
    public $auth_name = '';
    /**
     * @var string
     */
    public $auth_pass = '';

    /**
     * @param $use
     */
    public function useAuth($use) {
        $this->authentication = 0;
        if ($use == true)
            $this->authentication = 1;
    }

    /**
     * @param $name
     */
    public function setName($name) {
        $this->auth_name = $name;
    }

    /**
     * @param $pass
     */
    public function setPass($pass) {
        $this->auth_pass = $pass;
    }

    /**
     * @param $url
     * @param bool $followlocation
     * @param int $timeOut
     * @param int $maxRedirecs
     * @param bool $binaryTransfer
     * @param bool $includeHeader
     * @param bool $noBody
     */
    public function __construct($url, $followlocation = true, $timeOut = 30, $maxRedirecs = 4, $binaryTransfer = false, $includeHeader = false, $noBody = false) {
        $this->_url = $url;
        $this->_followlocation = $followlocation;
        $this->_timeout = $timeOut;
        $this->_maxRedirects = $maxRedirecs;
        $this->_noBody = $noBody;
        $this->_includeHeader = $includeHeader;
        $this->_binaryTransfer = $binaryTransfer;

        $this->_cookieFileLocation = dirname(__FILE__) . '/cookie.txt';
    }

    /**
     * @param $referer
     */
    public function setReferer($referer) {
        $this->_referer = $referer;
    }

    /**
     * @param $path
     */
    public function setCookiFileLocation($path) {
        $this->_cookieFileLocation = $path;
    }

    /**
     * @param $postFields
     */
    public function setPost($postFields) {
        $this->_post = true;
        $this->_postFields = $postFields;
    }

    /**
     * @param $userAgent
     */
    public function setUserAgent($userAgent) {
        $this->_useragent = $userAgent;
    }

    /**
     * @param string $url
     */
    public function createCurl($url = 'nul') {
        if ($url != 'nul') {
            $this->_url = $url;
        }

        $s = curl_init();

        curl_setopt($s, CURLOPT_URL, $this->_url);
        curl_setopt($s, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($s, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($s, CURLOPT_MAXREDIRS, $this->_maxRedirects);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($s, CURLOPT_FOLLOWLOCATION, $this->_followlocation);
        curl_setopt($s, CURLOPT_COOKIEJAR, $this->_cookieFileLocation);
        curl_setopt($s, CURLOPT_COOKIEFILE, $this->_cookieFileLocation);

        if ($this->authentication == 1) {
            curl_setopt($s, CURLOPT_USERPWD, $this->auth_name . ':' . $this->auth_pass);
        }
        if ($this->_post) {
            curl_setopt($s, CURLOPT_POST, true);
            curl_setopt($s, CURLOPT_POSTFIELDS, $this->_postFields);
        }

        if ($this->_includeHeader) {
            curl_setopt($s, CURLOPT_HEADER, true);
        }

        if ($this->_noBody) {
            curl_setopt($s, CURLOPT_NOBODY, true);
        }
        /*
          if($this->_binary)
          {
          curl_setopt($s,CURLOPT_BINARYTRANSFER,true);
          }
         */
        curl_setopt($s, CURLOPT_USERAGENT, $this->_useragent);
        curl_setopt($s, CURLOPT_REFERER, $this->_referer);

        $this->_webpage = curl_exec($s);
        //Common::printArr(curl_getinfo($s));
        $this->_status = curl_getinfo($s, CURLINFO_HTTP_CODE);
        if (!$this->_status) {
            $this->_webpage = file_get_contents($this->_url);
        }
        curl_close($s);
    }

    /**
     * @return int
     */
    public function getHttpStatus() {
        return $this->_status;
    }

    /**
     * @return string
     */
    public function __tostring() {
        return $this->_webpage;
    }

    /**
     * @param $url
     * @return mixed|string
     */
    public static function getFileContent($url) {

        $cookie = tmpfile();
        $userAgent = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31';

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $userAgent);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        $data = curl_exec($ch);
        curl_close($ch);
        if(!$data){
            $data = file_get_contents($url);
        }
        return $data;
    }

}
