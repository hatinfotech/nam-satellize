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
 * Description of System_Security_Captcha
 *
 * @author hatmt_000
 */
class System_Security_Captcha {

    private $namespace = 'captcha';

    public function init() {
        
    }

    public function __construct($namespace = null) {
        $this->init();
        $this->namespace = $namespace;
    }

    public static function getInstance($namespace = null) {
        return new System_Security_Captcha($namespace);
    }

    public function getSecureImage() {
        $ranStr = md5(microtime());    // Lấy chuỗi rồi mã hóa md5
        $ranStr = substr($ranStr, 0, 6);    // Cắt chuỗi lấy 6 ký tự
        $_SESSION[$this->namespace] = $ranStr; // Lưu giá trị vào session
        $newImage = imagecreatefromjpeg("bg_captcha.jpg"); // Tạo hình ảnh từ bg_captcha.jpg
        $txtColor = imagecolorallocate($newImage, 0, 0, 0); // Thêm màu sắc cho hình ảnh 
        imagestring($newImage, 5, 5, 5, $ranStr, $txtColor); // Vẽ ra chuỗi string
        header("Content-type: image/jpeg");    // Xuất định dạng là hình ảnh
        imagejpeg($newImage); // Xuất hình ảnh ra trình như 1 file
    }

}
