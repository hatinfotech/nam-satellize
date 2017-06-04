<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 18/3/2017
 * Time: 10:16 AM
 */
class Db {

    private static $self;

    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var string
     */
    protected $database;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $timezone;

    /**
     * @var string
     */
    protected $charset = 'utf8';

    /**
     * @var Resource
     */
    protected $connection;

    /**
     * @param $overrideConfig
     */
    function __construct($overrideConfig) {
        $dbConfig = Config_Parameter::g(K::DB);
        $this->hostname = $dbConfig['hostname'];
        $this->database = $dbConfig['database'];
        $this->username = $dbConfig['username'];
        $this->password = $dbConfig['password'];
        $this->timezone = $dbConfig['timezone'];
        $this->charset = $dbConfig['charset'];

        $this->connection = mysql_pconnect($this->hostname, $this->username, $this->password) or trigger_error(mysql_error(), E_USER_ERROR);
        mysql_query("set names '" . $this->charset . "'");
        date_default_timezone_set($this->timezone);
        mysql_select_db($this->database, $this->connection);
    }

    public function connect() {
        $this->connection = mysql_pconnect($this->hostname, $this->username, $this->password) or trigger_error(mysql_error(), E_USER_ERROR);
    }

    /**
     * @param $overrideConfig
     * @return Db
     */
    public static function getInstance($overrideConfig = NULL) {
        if (!self::$self) {
            self::$self = new self($overrideConfig);
        }
        return self::$self;
    }

    /**
     * get instance
     * @return Db
     */
    public static function g() {
        return self::getInstance();
    }

    /**
     * execute query
     * @param $queryStr
     * @return resource
     */
    public static function q($queryStr) {
        return self::g()->query($queryStr);
    }

    /**
     * Query to array
     * @param $queryStr
     * @param $option [ K::IndexColumns => col | [ col1, col2 ], K::ValueColumn=>col ]
     * @return array
     */
    public static function a($queryStr, $option = array()) {
        return self::g()->toArray($queryStr, $option);
    }

    /**
     * Get first
     * @param $queryString
     * @return array
     */
    public static function f($queryString) {
        return self::g()->getFirst($queryString);
    }

    /**
     * Check for exists
     * @param $queryString
     * @return bool|int
     */
    public static function e($queryString) {
        return self::g()->checkExists($queryString);
    }

    /**
     * Get SQL value string
     * @param $theValue
     * @param string $theType
     * @param string $theDefinedValue
     * @param string $theNotDefinedValue
     * @return float|int|string
     */
    public static function s($theValue, $theType = K::text, $theDefinedValue = "", $theNotDefinedValue = "") {
        return self::getSQLValueString($theValue, $theType, $theDefinedValue, $theNotDefinedValue);
    }

    /**
     * @param $theValue
     * @param string $theType
     * @param string $theDefinedValue
     * @param string $theNotDefinedValue
     * @return float|int|string
     */
    public static function getSQLValueString($theValue, $theType = K::text, $theDefinedValue = "", $theNotDefinedValue = "") {
        if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        }

        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue, Db::g()->getConnection()) : mysql_escape_string($theValue);

        switch ($theType) {
            case K::text:
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case K::sqlKey:
                $theValue = ($theValue != "") ? "`" . $theValue . "`" : "NULL";
                break;
            case K::long:
            case K::int:
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case K::double:
                $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                break;
            case K::date:
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case K::defined:
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }

    public function query($queryStr) {
//        echo $queryStr."\n";
        $result = mysql_query($queryStr, $this->connection);
        if ($result === false) {
            throw new Exception(mysql_error($this->connection) . "\n<br>" . $queryStr, mysql_errno($this->connection));
        }
        return $result;
    }

    public function insertOrUpdate($table, $index, $data) {
        $indexVal = $data[$index];
        if (!Db::e("SELECT * FROM " . Db::s($table, K::sqlKey) . " WHERE " . Db::s($index, K::sqlKey) . " = " . Db::s($data[$index]) . " ")) {
            $keyList = '';
            $valList = '';
            foreach ($data as $key => $val) {
                $keyList .= "" . Db::s($key, K::sqlKey) . ", ";
                $valList .= " " . Db::s($val) . ", ";
            }
            $keyList = preg_replace('/, $/', '', $keyList);
            $valList = preg_replace('/, $/', '', $valList);

            if (!Db::q("insert into " . Db::s($table, K::sqlKey) . " ($keyList) values ($valList) ")) {
                throw new Exception(mysql_error());
            }
        } else {
            $keyValList = '';
            foreach ($data as $key => $val) {
                $keyValList .= "" . Db::s($key, K::sqlKey) . " = " . Db::s($val) . ", ";
            }
            $keyValList = preg_replace('/, $/', '', $keyValList);

            if (!Db::q("update " . Db::s($table, K::sqlKey) . " set $keyValList where " . Db::s($index, K::sqlKey) . " = " . Db::s($data[$index]) . " ")) {
                throw new Exception(mysql_error());
            }
        }
        return true;
    }

    public function count($queryStr) {
        return mysql_num_rows($this->query($queryStr));
    }

    public function checkExists($queryStr) {
        return $this->count($queryStr) > 0 ? true : 0;
    }

    /**
     * Query to array
     * @param $queryStr
     * @param $option [ K::IndexColumns => col | [ col1, col2 ], K::ValueColumn=>col ]
     * @return array
     */
    public function toArray($queryStr, $option = array()) {
        $link = $this->query($queryStr);
        $rows = array();
        while ($row = mysql_fetch_assoc($link)) {
            if ($option[K::IndexColumns]) {
                if (is_array($option[K::IndexColumns])) {
                    $key = '';
                    foreach ($option[K::IndexColumns] as $col) {
                        $key .= ($row[$col] . '-');
                    }
                    $key = trim($key, '-');
                } else {
                    $key = $row[$option[K::IndexColumns]];
                }
                if ($option[K::ValueColumn]) {
                    $rows[$key] = $row[$option[K::ValueColumn]];
                } else {
                    $rows[$key] = $row;
                }
            } else {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    public function getFirst($queryStr) {
        return $this->fetch($this->query($queryStr));
    }

    public function fetch($queryLink) {
        return mysql_fetch_assoc($queryLink);
    }

    /**
     * @return Resource
     */
    public function getConnection() {
        return $this->connection;
    }

} 