<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 18/3/2017
 * Time: 09:45 AM
 */
class Config_Parameter {

    protected static $siteInfo;
    private static $self;
    protected $parameter;

    function __construct() {
        $this->parameter = array(
            K::ROUTE => array(
                '/^\/?$/' => array('Site', 'Main', 'index', K::parameter => array()),
                '/^api$/' => array('Site', 'Main', 'api', K::parameter => array()),
                '/^lien-he.html$/' => array('Site', 'Main', 'contact', K::parameter => array()),
                '/^page\/(.*)\.html$/' => array('Site', 'Main', 'article', K::parameter => array('UniqueKey')),
                '/^404\.html$/' => array('Site', 'Main', 'error404', K::parameter => array()),
                '/^thong-bao\.html$/' => array('Site', 'Main', 'notification', K::parameter => array()),
                '/^dang-nhap\.html$/' => array('Site', 'Admin', 'login', K::parameter => array()),
                '/^danh-sach-van-don\.html$/' => array('Site', 'Admin', 'ticketList', K::parameter => array()),
                '/^danh-sach-van-don\/page-([0-9]*)\.html$/' => array('Site', 'Admin', 'ticketList', K::parameter => array('page')),
                '/^thong-tin-van-don\/([^\/]*).html$/' => array('Site', 'Admin', 'ticketInfo', K::parameter => array('ticket')),
                '/^dang-ky\.html$/' => array('Site', 'Admin', 'register', K::parameter => array()),
                '/^dang-xuat\.html$/' => array('Site', 'Admin', 'logout', K::parameter => array()),
                '/^yeu-cau-van-chuyen\.html$/' => array('Site', 'Main', 'index', K::parameter => array()),
                '/^phan-loai-san-pham\/([^\/]*)\.html$/' => array('Site', 'Main', 'productCategory', K::parameter => array('cateUniqueKey')),
                '/^phan-loai-san-pham\/([^\/]*)\/trang-([0-9]*)\.html$/' => array('Site', 'Main', 'productCategory', K::parameter => array('cateUniqueKey','page')),
                '/^([^\/]*)\.html$/' => array('Site', 'Main', 'product', K::parameter => array('uniqueKey')),
            ),
            K::DB => array(
                //K::hostname => 'localhost',
                //K::database => 'shippers_db',
                //K::username => 'shippers',
                //K::password => '6a6e3u8yn',
                //K::timezone => 'Asia/Saigon',
                //K::charset => 'utf8',
            ),
            K::SITE_CODE => 'localhost',
            K::COPYRIGHT => '© Copyright 2006 - 2017 by vitinhsmyou.com. All Rights Reserved',
            K::DEFAULT_TEMPLATE_PATH => '/Src/Template/Default',
            K::DEFAULT_TEMPLATE_DIR => BASE_DIR . '/Src/Template/Default',
            K::DEFAULT_TEMPLATE_INDEX => 'index.php',
            K::BASE_PATH => '',
            K::SECURE_SALT => '(*(*NIU*&TIJFR^&*(*%$%',
            K::NAM_API_URl => '',
//            K::NAM_API_URl => 'http://enterprise.namsoftware.com',
            K::uploadDir => BASE_DIR . '/upload',
            K::uploadPath => '/upload',
        );
    }

    /**
     * Get instance object
     * @throws Exception
     * @return Config_Parameter
     */
    public static function getInstance() {
        if (!self::$self) {
            $serverName = str_replace('.', '', $_SERVER['SERVER_NAME']);
            $serverName[0] = strtoupper($serverName[0]);
            $configClass = "Config_{$serverName}_Parameter";
            //echo $configClass;
            //exit;
            if (!class_exists($configClass)) {
                die('Config \'' . $configClass . '\' was not found');
                //throw new Exception('Config was not found');
            }
            self::$self = new $configClass();
        }
        return self::$self;
    }

    public static function  g($key) {
        return self::getInstance()->getParameterByKey($key);
    }

    public static function getSiteInfo($key = NULL) {
        if (!self::$siteInfo) {
            self::$siteInfo = Db::getInstance()->toArray("SELECT `Name`, `Value` FROM `web_parameters` WHERE `Name` LIKE 'WEB_%'", array(
                'IndexColumns' => 'Name',
                'ValueColumn' => 'Value'
            ));
        }
        return $key ? self::$siteInfo[$key] : self::$siteInfo;
    }

    public function getParameterByKey($key) {
        return $this->parameter[$key];
    }

}