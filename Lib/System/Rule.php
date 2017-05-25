<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/6/2016
 * Time: 10:28 PM
 */
class System_Rule {

    /**
     * @var System_Rule
     */
    private static $container;

    /**
     * Constructor
     */
    public function __construct() {

    }

    /**
     * Get instance
     * @return System_Rule
     */
    public static function getInstance() {
        if (!self::$container) {
            self::$container = new self();
        }
        return self::$container;
    }


    /**
     * @param $username
     * @return bool
     */
    public static function usernameFilter($username) {
        if (!$username) {
            System::busAndLogError('Username could not be null');
            return false;
        }
        return true;
    }

    /**
     * @param $password
     * @return bool
     */
    public static function passwordFilter($password) {
        if (strlen($password) < 6) {
            System::busAndLogError('Password length must be large then 6 character');
            return false;
        }
        return true;
    }

    /**
     * @param $email
     * @return bool
     */
    public static function emailFilter($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            System::busAndLogError('Email was not valid');
        }
        return true;
    }

    public static function phoneFilter($phone) {
        return true;
    }

    /**
     * Get
     * @param $config
     * @param string $defaultValue
     * @return bool
     */
    public static function get($config, $defaultValue = NULL) {
        return Model_Business_Parameter::get($config, $defaultValue);
    }

} 