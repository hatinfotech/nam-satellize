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
 * Description of System_Security_SimpleCaptcha
 *
 * @author hatmt_000
 */
include("/Lib/SimplePhpCaptcha/simple-php-captcha.php");

class System_Security_SimpleCaptcha {

    private $namespace = 'simple_captcha';
    private $isCaseInsensitiveCheck = true;

    public function init() {
        
    }

    public function __construct($namespace = null) {
        if ($namespace) {
            $this->namespace = $namespace;
        }
        $this->init();
    }

    public static function getInstance($namespace = null) {
        return new System_Security_SimpleCaptcha($namespace);
    }

    public function getSecureImage() {
        Session::set($this->namespace, simple_php_captcha());
        $session = Session::get($this->namespace);
        return $session['image_src'];
    }

    public function checkCaptcha($captcha) {
        $sesion = Session::get($this->namespace);
        if ($this->isCaseInsensitiveCheck) {
            return strtolower($sesion['code']) == strtolower($captcha);
        }
        return $sesion['code'] == $captcha;
    }

    public function getIsCaseInsensitiveCheck() {
        return $this->isCaseInsensitiveCheck;
    }

    public function setIsCaseInsensitiveCheck($isCaseInsensitiveCheck) {
        $this->isCaseInsensitiveCheck = $isCaseInsensitiveCheck;
        return $this;
    }

}
