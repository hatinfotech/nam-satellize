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
 * Description of System_Security_Recaptcha
 *
 * @author hatmt_000
 */
require_once('recaptchalib.php');

class System_Security_Recaptcha {

    private $publickey = "";
    private $privatekey = "";

    /**
     * the response from reCAPTCHA
     */
    private $resp = null;

    /**
     * the error code from reCAPTCHA, if any
     */
    private $error = null;

    public function __construct($namespace = null) {
        $this->init();
        $this->namespace = $namespace;
    }

    public static function getInstance($namespace = null) {
        return new System_Security_Recaptcha($namespace);
    }

}
