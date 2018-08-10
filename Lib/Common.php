<?php
//error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT ^ E_WARNING);
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT ^ E_WARNING);
session_start();
define('BASE_DIR', realpath(dirname(__FILE__) . '/..'));

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(BASE_DIR . '/Lib'),
    realpath(BASE_DIR . '/Src/Module'),
    get_include_path(),
)));

function __autoload($namespace) {
    $fileInclude = str_replace('\\', '/', preg_replace('/(.)_(.)/', '$1/$2', $namespace)) . '.php';
    include_once($fileInclude);
}

class Common {

    public static $siteInfo;

    static function getSiteInfo($key = NULL) {
        if (!self::$siteInfo) {
            self::$siteInfo = Db::a("SELECT Name, `Value` FROM `web_parameters` WHERE Name LIKE 'WEB_META_%' || Name LIKE 'WEB_INFO_%'", array(
                K::IndexColumns => K::Name,
                K::ValueColumn => K::Value
            ));
        }
        return $key ? self::$siteInfo[$key] : self::$siteInfo[$key];
    }

    /**
     * Get widget
     * @param $code
     * @return array
     */
    public static function getWidget($code) {
        $widget = Db::f("SELECT * FROM web_widget WHERE Code = " . Db::s($code) . " ");
        return $widget;
    }

    /**
     * Get menu
     * @return array
     */
    public static function getMenu() {
        $menu = Db::a("SELECT * FROM web_menu ORDER BY `Order` ASC ");
        return $menu;
    }

    /**
     * Get menu
     * @return array
     */
    public static function getProductCategory() {
        $menu = Db::a("SELECT * FROM product_category");
        return $menu;
    }

    /**
     * Get menu
     * @return array
     */
    public static function getBanner($code) {
        $banner = Db::f("SELECT * FROM web_banner WHERE `Code` = " . Db::s($code));
        $banner[K::Details] = Db::a("SELECT * FROM web_banner_detail WHERE Banner = " . Db::s($banner[K::Id]) . " ORDER BY `Order`");
        return $banner;
    }

    public static function error404Redirect() {
        header('HTTP/1.0 404 Not Found', true, 404);
    }

    public static function redirect($path) {
        header("Location: {$path}");
    }

    public static function requireLogin($message, $back = null) {
        $_SESSION[K::LAST_NOTIFICATION] = $message;
        self::redirect('/dang-nhap.html?back=' . urlencode($back));
    }

    /**
     * @param $str
     * @return mixed
     */
    public static function conv2NoneViStri($str) {
        $str = preg_replace("/(̣|̉|̣̣|̀|́|̃)/", '', $str);
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ắ|ằ|� �|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|� �|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|� �|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|� �|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }

    /**
     * @param $str
     * @param array $option
     * @return mixed|string
     */
    public static function conv2NoneStri($str, $option = array('isLowerCase' => false, 'separationCharacter' => '_')) {
        list($isLowerCase, $separationCharacter) = $option;
        $str = self::conv2NoneViStri($str);
        $str = preg_replace('/[^A-Za-z0-9\-]{1,}/', $separationCharacter, $str);
        if ($isLowerCase) {
            return strtolower($str);
        }
        return $str;
    }

    /**
     * Get locations by parent
     * @param $parent
     * @return array
     */
    public static function getLocationsByParent($parent) {
        return Db::a("SELECT Code,FullName,ShortName FROM location WHERE Parent = " . Db::s($parent) . " ORDER BY FullName ASC", array(K::IndexColumns => K::Code));
    }

    /**
     * Get locations by parent
     * @param $parent
     * @return array
     */
    public static function getBusinessLocationsByParent($parent) {
        $q = "
          SELECT Code,FullName,ShortName
          FROM location
          WHERE Parent = " . Db::s($parent) . "
                && (Code IN (SELECT District FROM trans_business_district)
                    || Code IN (SELECT Province FROM trans_business_district)
                    || Code IN (SELECT Location FROM trans_business_location))
          ORDER BY FullName ASC
          ";
        //echo $q;
        return Db::a($q, array(K::IndexColumns => K::Code));
    }

    public static function printArr($array) {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

    /**
     * @param $message string|Exception
     */
    public static function errorReport($message) {
        if (Bootstrap::g()->isCliMode()) {
            echo "$message\n";
        } else {
            if ($message instanceof Exception) {
                $_SESSION[K::LAST_EXCEPTION] = $message;
                $_SESSION[K::LAST_NOTIFICATION] = $message->getMessage();
            } else {
                $_SESSION[K::LAST_NOTIFICATION] = $message;
            }
            //Common::printArr($_SESSION);
            //exit;
            header('Location: /thong-bao.html');
        }
    }

    public static function notify($message) {
        $_SESSION[K::LAST_NOTIFICATION] = $message;
        header('Location: /thong-bao.html');
    }

    public static function checkProcessRunning($pid) {
        $output = array();
        $return = null;
        if (Config_Parameter::g(K::PLATFORM) == 'windows') {
            exec('tasklist /fi "PID eq ' . $pid . '"', $output, $return);
            echo "Check previous process \$output = ";
            print_r($output);
            echo "\n";
            return $output[2] ? true : false;
        } elseif (Config_Parameter::g(K::PLATFORM) == 'linux') {
            exec("ps x | grep $pid", $output, $return);
            foreach ($output as $item) {
                if (preg_match('/^' . $pid . '/', $item, $matched)) {
                    return true;
                }
                //                print_r($matched);
            }
            //            print_r($output);
            return false;
        }
        throw new Exception_Business('Check process for ' . Config_Parameter::g(K::PLATFORM) . ' was not support');
    }

    public static function getFileSize($file) {
        // filesize will only return the lower 32 bits of
        // the file's size! Make it unsigned.
        $fmod = filesize($file);
        if ($fmod < 0) $fmod += 2.0 * (PHP_INT_MAX + 1);

        // find the upper 32 bits
        $i = 0;

        $myfile = fopen($file, "r");

        // feof has undefined behaviour for big files.
        // after we hit the eof with fseek,
        // fread may not be able to detect the eof,
        // but it also can't read bytes, so use it as an
        // indicator.
        while (strlen(fread($myfile, 1)) === 1) {
            fseek($myfile, PHP_INT_MAX, SEEK_CUR);
            $i++;
        }

        fclose($myfile);

        // $i is a multiplier for PHP_INT_MAX byte blocks.
        // return to the last multiple of 4, as filesize has modulo of 4 GB (lower 32 bits)
        if ($i % 2 == 1) $i--;

        // add the lower 32 bit to our PHP_INT_MAX multiplier
        return ((float)($i) * (PHP_INT_MAX + 1)) + $fmod;
    }
}

function __trans($message) {
    return $message;
}