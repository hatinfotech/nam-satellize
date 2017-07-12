<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 18/3/2017
 * Time: 07:49 AM
 */
class WebApiServer {

    private $secureSalt;
    private $timeout;

    function __construct() {
        $this->secureSalt = Config_Parameter::g('SECURE_SALT');
    }

    protected function checkTokenKey($tokenKey) {
        return true ?: $tokenKey == $requestTokenKey = md5($this->secureSalt . ((int)((time() / $this->timeout))));
    }

    /**
     * postData[command=?[,...]]
     *  command=updateParameter => postData[parameter]
     * @param $getData
     * @param $postData
     * @param $filesData
     * @return array
     * @throws Exception
     */
    public function post($getData, $postData, $filesData) {
//        print_r($getData);
//        print_r($postData);
//        print_r($filesData);
//        return array(
//            K::returnKey => true
//        );
        try {
            if (!$this->checkTokenKey($getData['tokenKey'])) {
                throw new Exception('Token key was not matched');
            }
            $command = $getData['command'];
            switch ($command) {
                case C::updateParameter:
                    $this->updateParameter($postData, $filesData);
                    break;
                case C::updateMenu:
                    $this->updateMenu($postData, $filesData);
                    break;
                case C::updateBanner:
                    $this->updateBanner($postData, $filesData);
                    break;
                case C::getProductCodeList:
                    $productCodeList = $this->getProductCodeList();
                    if ($productCodeList === false) {
                        throw new Exception('System could not get product code list');
                    }
                    return array(
                        K::returnKey => true,
                        K::productCodeList => $productCodeList
                    );
                    break;
                case C::addNewProducts:
                    $this->addNewProducts($postData, $filesData);
                    break;
                case C::removeProducts:
                    $this->removeProducts($postData, $filesData);
                    break;
                case C::updateProducts:
                    $this->updateProducts($postData, $filesData);
                    break;
                case C::updateProductCategories:
                    $this->updateProductCategories($postData, $filesData);
                    break;
                case C::updateProductInCategory:
                    $this->updateProductInCategory($postData, $filesData);
                    break;
                case C::updatePostCategories:
                    $this->updatePostCategories($postData, $filesData);
                    break;
                case C::updatePosts:
                    $this->updatePosts($postData, $filesData);
                    break;
                case C::updateWidgets:
                    $this->updateWidgets($postData, $filesData);
                    break;
                case C::updateTransBusinessLocation:
                    $this->updateTransBusinessLocation($postData, $filesData);
                    break;
                case C::cleanTransBusinessLocation:
                    $this->cleanTransBusinessLocation($postData, $filesData);
                    break;
                case C::createTransBusinessLocation:
                    $this->createTransBusinessLocation($postData, $filesData);
                    break;
            }
            return array(
                K::returnKey => true
            );
        } catch (Exception $e) {
            return array(
                K::returnKey => false,
                K::logs => array(
                    $e->getMessage()
                )
            );
        }
    }

    /**
     * @param $postData
     * @param $fileData
     * @throws Exception
     * @return bool
     */
    public function updateParameter($postData, $fileData) {

        foreach ($postData as $name => $parameter) {
            if (!Db::g()->checkExists($q = "SELECT * FROM `web_parameters` WHERE `Site` = " . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE), K::text) . " &&`Name` = " . Db::getSQLValueString($name, K::text))) {
                $result = Db::g()->query($q = "INSERT INTO `web_parameters` (`Site`, `Name`, `Value`, `Type`) VALUES (" . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE), K::text) . "," . Db::getSQLValueString($parameter[K::Name], K::text) . "," . Db::getSQLValueString($parameter[K::Value], K::text) . "," . Db::getSQLValueString($parameter[K::Type], K::text) . ")");
            } else {
                $result = Db::g()->query($q = "UPDATE `web_parameters` SET `Value` = " . Db::getSQLValueString($parameter[K::Value], K::text) . ", `Type` = " . Db::getSQLValueString($parameter[K::Type], K::text) . " WHERE `Site` = " . Db::getSQLValueString($parameter[K::Site], K::text) . " && `Name` = " . Db::getSQLValueString($parameter[K::Name], K::text));
            }
            if (!$result) {
                throw new Exception('System could not update parameter for key \'' . $parameter[K::Name] . '\' ');
            }
        }

        foreach ($fileData as $name => $fileItem) {
            $uploadFile = Config_Parameter::g(K::uploadDir) . '/' . $fileItem[K::name][K::Value];
            //echo $fileItem[K::tmp_name][K::Value]."\n";
            //echo $uploadFile."\n";
            if (!move_uploaded_file($fileItem[K::tmp_name][K::Value], $uploadFile)) {
                throw new Exception('System could not move upload file');
            }

            try {

                if (!Db::g()->checkExists($q = "SELECT * FROM `web_parameters` WHERE `Site` = " . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE), K::text) . " &&`Name` = " . Db::getSQLValueString($name, K::text))) {
                    $result = Db::g()->query($q = "INSERT INTO `web_parameters` (`Site`, `Name`, `Value`, `Type`) VALUES (" . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE), K::text) . "," . Db::getSQLValueString($name, K::text) . "," . Db::getSQLValueString($fileItem[K::name][K::Value], K::text) . "," . Db::getSQLValueString('IMAGE', K::text) . ")");
                } else {
                    $result = Db::g()->query($q = "UPDATE `web_parameters` SET `Value` = " . Db::getSQLValueString($fileItem[K::name][K::Value], K::text) . " WHERE `Site` = " . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE), K::text) . " && `Name` = " . Db::getSQLValueString($name, K::text));
                }
                if (!$result) {
                    throw new Exception('System could not update parameter for key \'' . $name . '\' ');
                }
            } catch (Exception $e) {
                throw new Exception($e->getMessage() . "\n$q", $e->getCode(), $e);
            }
        }

        return true;
    }

    public function cleanTransBusinessLocation($postData, $fileData) {
        Db::bt();
        if (!Db::q("DELETE FROM trans_business_location")) {
            Db::rt();
            throw new Exception_Business('System could not clean business location');
        }
        if (!Db::q("DELETE FROM trans_business_district")) {
            Db::rt();
            throw new Exception_Business('System could not clean business district');
        }
        Db::ct();
        return true;
    }

    public function createTransBusinessLocation($postData, $fileData) {
        $postData = $postData[K::data];
        //print_r($postData);
        if (!Db::g()->query($q = "INSERT INTO trans_business_district (Id, District, Province, Note) VALUES (" . Db::getSQLValueString($postData[K::Id], K::int) . "," . Db::getSQLValueString($postData[K::District], K::text) . "," . Db::getSQLValueString($postData[K::Province], K::text) . "," . Db::getSQLValueString($postData[K::Note], K::text) . ")")) {
            Db::rt();
            throw new Exception_Business('System could not sync transport business district');
        }
        foreach ($postData[K::Locations] as $location) {
            if (!Db::g()->query($q = "INSERT INTO trans_business_location (Id, BusinessDistrict, Location) VALUES (" . Db::getSQLValueString($location[K::Id], K::int) . "," . Db::getSQLValueString($postData[K::Id], K::int) . "," . Db::getSQLValueString($location[K::Location], K::text) . ")")) {
                Db::rt();
                throw new Exception_Business('System could not sync transport business district');
            }
        }
        return true;
    }
    /**
     * @param $postData
     * @param $fileData
     * @throws Exception
     * @return bool
     */
    public function updateTransBusinessLocation($postData, $fileData) {

        Db::bt();
        if (!Db::q("DELETE FROM trans_business_location")) {
            Db::rt();
            throw new Exception_Business('System could not clean business location');
        }
        if (!Db::q("DELETE FROM trans_business_district")) {
            Db::rt();
            throw new Exception_Business('System could not clean business district');
        }

        foreach ($postData as $name => $businessLocation) {
            if (!Db::g()->query($q = "INSERT INTO trans_business_district (Id, District, Province, Note) VALUES (" . Db::getSQLValueString($businessLocation[K::Id], K::int) . "," . Db::getSQLValueString($businessLocation[K::District], K::text) . "," . Db::getSQLValueString($businessLocation[K::Province], K::text) . "," . Db::getSQLValueString($businessLocation[K::Note], K::text) . ")")) {
                Db::rt();
                throw new Exception_Business('System could not sync transport business district');
            }
            foreach ($businessLocation[K::Locations] as $location) {
                if (!Db::g()->query($q = "INSERT INTO trans_business_location (Id, BusinessDistrict, Location) VALUES (" . Db::getSQLValueString($location[K::Id], K::int) . "," . Db::getSQLValueString($businessLocation[K::Id], K::int) . "," . Db::getSQLValueString($location[K::Location], K::text) . ")")) {
                    Db::rt();
                    throw new Exception_Business('System could not sync transport business district');
                }
            }

        }
        Db::ct();
        return true;
    }

    /**
     * @param $postData
     * @param $fileData
     * @throws Exception
     * @return bool
     */
    public function updateMenu($postData, $fileData) {
//        print_r($postData);
        $presentMenu = array();
        foreach ($postData as $code => $item) {
            if (!Db::g()->checkExists($q = "SELECT * FROM `web_menu` WHERE `Site` = " . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE), K::text) . " && `Code` = " . Db::getSQLValueString($code, K::text))) {
                $result = Db::g()->query($q = "INSERT INTO `web_menu` (`Site`, `Code`, `Parent`, `Name`, `Link`,`Order`) VALUES (" . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE), K::text) . "," . Db::getSQLValueString($item[K::Code], K::text) . "," . Db::getSQLValueString($item[K::Parent], K::text) . "," . Db::getSQLValueString($item[K::Name], K::text) . "," . Db::getSQLValueString($item[K::Link], K::text) . "," . Db::getSQLValueString($item[K::Order], K::int) . ")");
            } else {
                $result = Db::g()->query($q = "UPDATE `web_menu` SET `Parent` = " . Db::getSQLValueString($item[K::Parent], K::text) . ", `Name` = " . Db::getSQLValueString($item[K::Name], K::text) . ", `Link` = " . Db::getSQLValueString($item[K::Link], K::text) . ", `Order` = " . Db::getSQLValueString($item[K::Order], K::int) . " WHERE `Site` = " . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE), K::text) . " && `Code` = " . Db::getSQLValueString($item[K::Code], K::text));
            }
            if (!$result) {
                throw new Exception('System could not update menu for key \'' . $item[K::Name] . '\' ');
            }
            $presentMenu[] = Db::getSQLValueString($code);
        }

        // Remove some menu not in present
        Db::g()->query($q = "DELETE FROM web_menu WHERE `Code` NOT IN (" . (implode(',', $presentMenu) ?: '-1') . ")");
//        echo $q;
        return true;
    }

    /**
     * @param $postData
     * @param $fileData
     * @throws Exception
     * @return bool
     */
    public function updateBanner($postData, $fileData) {

//        print_r($postData);

        $currentBanners = Db::g()->toArray("SELECT * FROM `web_banner` WHERE `Site` = " . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE), K::text) . "", array(
            K::IndexColumns => K::Code
        ));

        foreach ($currentBanners as $currCode => $currentBanner) {
            $flag = false;
            foreach ($postData as $newCode => $newBanner) {
                if ($currCode == $newCode) {
                    $flag = true;
                    break;
                }
            }
            if (!$flag) {
                $currBannerDetails = Db::g()->toArray("SELECT * FROM `web_banner_detail` WHERE `Banner` = " . Db::getSQLValueString($currCode, K::text) . " ");
                foreach ($currBannerDetails as $currBannerDetailId => $currBannerDetail) {
                    unlink(Config_Parameter::g(K::uploadDir) . '/' . $currBannerDetail[K::Image]);
                }
                Db::g()->query("DELETE FROM `web_banner_detail` WHERE `Banner` = " . Db::getSQLValueString($currCode, K::text) . " ");
                Db::g()->query("DELETE FROM `web_banner` WHERE `Code` = " . Db::getSQLValueString($currCode, K::text) . " ");

            }

        }

        foreach ($postData as $newCode => $newBanner) {
            $flag = false;
            foreach ($currentBanners as $currCode => $currentBanner) {
                if ($currCode == $newCode) {
                    $flag = true;
                    break;
                }
            }

            if (!$flag) {

                if (!Db::g()->query("INSERT INTO `web_banner` (Id, Site, Code, Name) VALUE (" . Db::getSQLValueString($newCode, K::int) . "," . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE), K::text) . "," . Db::getSQLValueString($newBanner[K::Code], K::text) . "," . Db::getSQLValueString($newBanner[K::Name], K::text) . ")")) {
                    throw new Exception('System could not create web banner');
                }

                foreach ($newBanner[K::Details] as $dKey => $newBannerDetail) {
                    move_uploaded_file($fileData[$newCode][K::tmp_name][K::Details][$dKey][K::Image], Config_Parameter::g(K::uploadDir) . '/' . $fileData[$newCode][K::name][K::Details][$dKey][K::Image]);
                    if (!Db::g()->query("INSERT INTO `web_banner_detail` (Id, Banner, Title, Content, Image, `Order`) VALUES (" . Db::getSQLValueString($newBannerDetail[K::Id], K::text) . "," . Db::getSQLValueString($newCode, K::text) . "," . Db::getSQLValueString($newBannerDetail[K::Title], K::text) . "," . Db::getSQLValueString($newBannerDetail[K::Content], K::text) . "," . Db::getSQLValueString($fileData[$newCode][K::name][K::Details][$dKey][K::Image], K::text) . "," . Db::getSQLValueString($newBannerDetail[K::Order], K::text) . ")")) {
                        throw new Exception('System could not add web banner detail');
                    }
                }


            }

        }

        foreach ($postData as $newCode => $newBanner) {
            foreach ($currentBanners as $currCode => $currentBanner) {
                if ($currCode == $newCode) {

                    $currentBannerDetails = Db::g()->toArray("SELECT * FROM  `web_banner_detail` WHERE Banner = " . Db::getSQLValueString($currCode, K::text) . " ", array(
                        K::IndexColumns => K::Id
                    ));
                    foreach ($currentBannerDetails as $currDetailId => $currentBannerDetail) {
                        $flag = false;
                        foreach ($newBanner[K::Details] as $newDetailId => $newBannerDetail) {
                            if ($currDetailId == $newDetailId) {
                                $flag = true;
                            }
                        }
                        if (!$flag) {
                            Db::g()->query("DELETE FROM `web_banner_detail` WHERE Id = " . Db::getSQLValueString($currDetailId, K::int) . " ");
                        }

                    }


                    foreach ($newBanner[K::Details] as $newDetailId => $newBannerDetail) {
                        $flag = false;
                        foreach ($currentBannerDetails as $currDetailId => $currentBannerDetail) {
                            if ($currDetailId == $newDetailId) {
                                $flag = true;
                            }
                        }
                        if (!$flag) {
                            move_uploaded_file($fileData[$newCode][K::tmp_name][K::Details][$newDetailId][K::Image], Config_Parameter::g(K::uploadDir) . '/' . $fileData[$newCode][K::name][K::Details][$newDetailId][K::Image]);
                            if (!Db::g()->query("INSERT INTO `web_banner_detail` (Id, Banner, Title, Content, Image, `Order`) VALUES (" . Db::getSQLValueString($newBannerDetail[K::Id], K::int) . "," . Db::getSQLValueString($newCode, K::text) . "," . Db::getSQLValueString($newBannerDetail[K::Title], K::text) . "," . Db::getSQLValueString($newBannerDetail[K::Content], K::text) . "," . Db::getSQLValueString($fileData[$newCode][K::name][K::Details][$newDetailId][K::Image], K::text) . "," . Db::getSQLValueString($newBannerDetail[K::Order], K::int) . ")")) {
                                throw new Exception('System could not add web banner detail');
                            }
                        }

                    }


                    foreach ($newBanner[K::Details] as $newDetailId => $newBannerDetail) {
                        foreach ($currentBannerDetails as $currDetailId => $currentBannerDetail) {
                            if ($currDetailId == $newDetailId) {
                                unlink(Config_Parameter::g(K::uploadDir) . '/' . $currentBannerDetail[K::Image]);
                                move_uploaded_file($fileData[$newCode][K::tmp_name][K::Details][$newDetailId][K::Image], Config_Parameter::g(K::uploadDir) . '/' . $fileData[$newCode][K::name][K::Details][$newDetailId][K::Image]);
                                if (!Db::g()->query("UPDATE `web_banner_detail` SET Title = " . Db::getSQLValueString($newBannerDetail[K::Title], K::text) . ", Content = " . Db::getSQLValueString($newBannerDetail[K::Content], K::text) . ",Image = " . Db::getSQLValueString($fileData[$newCode][K::name][K::Details][$newDetailId][K::Image], K::text) . ", `Order` = " . Db::getSQLValueString($newBannerDetail[K::Order], K::int) . " WHERE Id = " . Db::getSQLValueString($newDetailId, K::int) . " ")) {
                                    throw new Exception('System could not add web banner detail');
                                }
                            }
                        }
                    }

                    break;
                }
            }

        }

        return true;
    }

    public function getProductCodeList() {
        return Db::g()->toArray("SELECT `Code` FROM `product` WHERE Site = " . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE)) . " ") ?: array();
    }

    public function addNewProducts($postData, $fileData) {
        foreach ($postData as $newProductCode => $newProduct) {
            if (Db::g()->checkExists("SELECT Code FROM product WHERE Code = " . Db::getSQLValueString($newProductCode) . " ")) {
                Db::g()->query("DELETE FROM `product` WHERE Code = " . Db::getSQLValueString($newProductCode) . " ");
            }
            move_uploaded_file($fileData[$newProductCode][K::tmp_name][K::PictureId], Config_Parameter::g(K::uploadDir) . '/' . $fileData[$newProductCode][K::name][K::PictureId]);
            if (!Db::g()->query("INSERT INTO product (Code, Barcode, ParentId, ManualCode, Name, Description, Technical, UniqueKey, ManufactureId, Keyword, WarehouseUnit, ExchangeProduct, UnitRatio, IsApprove, CreatorId, Created, State, MarketingPrice, TypeCode, Tax, PictureId, Site) VALUES (" . Db::getSQLValueString($newProduct[K::Code]) . ", " . Db::getSQLValueString($newProduct[K::Barcode]) . ", " . Db::getSQLValueString($newProduct[K::ParentId], K::int) . ", " . Db::getSQLValueString($newProduct[K::ManualCode]) . ", " . Db::getSQLValueString($newProduct[K::Name]) . ", " . Db::getSQLValueString($newProduct[K::Description]) . ", " . Db::getSQLValueString($newProduct[K::Technical]) . ", " . Db::getSQLValueString($newProduct[K::UniqueKey]) . ", " . Db::getSQLValueString($newProduct[K::ManufactureId], K::int) . ", " . Db::getSQLValueString($newProduct[K::Keyword]) . ", " . Db::getSQLValueString($newProduct[K::WarehouseUnit]) . ", " . Db::getSQLValueString($newProduct[K::ExchangeProduct]) . ", " . Db::getSQLValueString($newProduct[K::UnitRatio], K::double) . ", " . Db::getSQLValueString($newProduct[K::IsApprove], K::int) . ", " . Db::getSQLValueString($newProduct[K::CreatorId], K::int) . ", " . Db::getSQLValueString($newProduct[K::Created], K::date) . ", " . Db::getSQLValueString($newProduct[K::State]) . ", " . Db::getSQLValueString($newProduct[K::MarketingPrice]) . ", " . Db::getSQLValueString($newProduct[K::TypeCode]) . ", " . Db::getSQLValueString($newProduct[K::Tax]) . ", " . Db::getSQLValueString($fileData[$newProductCode][K::name][K::PictureId]) . ", " . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE)) . ")")) {
                throw new Exception(mysql_error());
            };
            foreach ($newProduct[K::Pictures] as $newPictureId => $newPicture) {
                move_uploaded_file($fileData[$newProductCode][K::tmp_name][K::Pictures][$newPictureId][K::FileId], Config_Parameter::g(K::uploadDir) . '/' . $fileData[$newProductCode][K::name][K::Pictures][$newPictureId][K::FileId]);
                if (Db::g()->checkExists("SELECT Id FROM product_picture WHERE Id = " . Db::getSQLValueString($newPictureId) . " ")) {
                    Db::g()->query("DELETE FROM `product_picture` WHERE Id = " . Db::getSQLValueString($newPictureId) . " ");
                }
                Db::g()->query("INSERT INTO product_picture (Id, Product, Description, FileId) VALUES (" . Db::getSQLValueString($newPicture[K::Id], K::int) . "," . Db::getSQLValueString($newPicture[K::Product]) . "," . Db::getSQLValueString($newPicture[K::Description]) . "," . Db::getSQLValueString($fileData[$newProductCode][K::name][K::Pictures][$newPictureId][K::FileId]) . ")");
            }
        }
    }

    public function removeProducts($postData, $fileData) {
        foreach ($postData as $productCode => $product) {
            $pictures = Db::g()->toArray("SELECT * FROM product_picture WHERE Product = " . Db::getSQLValueString($productCode) . " ");
            foreach ($pictures as $picture) {
                unlink(Config_Parameter::g(K::uploadDir) . '/' . $picture[K::FileId]);
            }
            Db::g()->query("DELETE FROM product_picture WHERE Product = " . Db::getSQLValueString($productCode) . " ");
            Db::g()->query("DELETE FROM product WHERE Code = " . Db::getSQLValueString($productCode) . " ");

        }
        return true;
    }

    /**
     * @param $postData
     * @param $fileData
     * @throws Exception
     * @return bool
     */
    public function updateProducts($postData, $fileData) {
        foreach ($postData as $productCode => $product) {
            if (!Db::g()->checkExists("SELECT Code FROM product WHERE Code = " . Db::getSQLValueString($productCode) . " ")) {
                move_uploaded_file($fileData[$productCode][K::tmp_name][K::PictureId], Config_Parameter::g(K::uploadDir) . '/' . $fileData[$productCode][K::name][K::PictureId]);
                if (!Db::g()->query("INSERT INTO product (Code, Barcode, ParentId, ManualCode, Name, Description, Technical, UniqueKey, ManufactureId, Keyword, WarehouseUnit, ExchangeProduct, UnitRatio, IsApprove, CreatorId, Created, State, MarketingPrice, TypeCode, Tax, PictureId, Site, Price, Discount) VALUES (" . Db::getSQLValueString($product[K::Code]) . ", " . Db::getSQLValueString($product[K::Barcode]) . ", " . Db::getSQLValueString($product[K::ParentId], K::int) . ", " . Db::getSQLValueString($product[K::ManualCode]) . ", " . Db::getSQLValueString($product[K::Name]) . ", " . Db::getSQLValueString($product[K::Description]) . ", " . Db::getSQLValueString($product[K::Technical]) . ", " . Db::getSQLValueString($product[K::UniqueKey]) . ", " . Db::getSQLValueString($product[K::ManufactureId], K::int) . ", " . Db::getSQLValueString($product[K::Keyword]) . ", " . Db::getSQLValueString($product[K::WarehouseUnit]) . ", " . Db::getSQLValueString($product[K::ExchangeProduct]) . ", " . Db::getSQLValueString($product[K::UnitRatio], K::double) . ", " . Db::getSQLValueString($product[K::IsApprove], K::int) . ", " . Db::getSQLValueString($product[K::CreatorId], K::int) . ", " . Db::getSQLValueString($product[K::Created], K::date) . ", " . Db::getSQLValueString($product[K::State]) . ", " . Db::getSQLValueString($product[K::MarketingPrice]) . ", " . Db::getSQLValueString($product[K::TypeCode]) . ", " . Db::getSQLValueString($product[K::Tax]) . ", " . Db::getSQLValueString($fileData[$productCode][K::name][K::PictureId]) . ", " . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE)) . ", " . Db::getSQLValueString($product[K::Price]) . ", 0)")) {
                    throw new Exception(mysql_error());
                };
            } else {
                unlink(Config_Parameter::g(K::uploadDir) . '/' . $product[K::PictureId]);
                move_uploaded_file($fileData[$productCode][K::tmp_name][K::PictureId], Config_Parameter::g(K::uploadDir) . '/' . $fileData[$productCode][K::name][K::PictureId]);
                if (!Db::g()->query("UPDATE product SET Code = " . Db::getSQLValueString($product[K::Code]) . ", Barcode = " . Db::getSQLValueString($product[K::Barcode]) . ", ParentId = " . Db::getSQLValueString($product[K::ParentId], K::int) . ", ManualCode = " . Db::getSQLValueString($product[K::ManualCode]) . ", Name = " . Db::getSQLValueString($product[K::Name]) . ", Description = " . Db::getSQLValueString($product[K::Description]) . ", Technical = " . Db::getSQLValueString($product[K::Technical]) . ", UniqueKey = " . Db::getSQLValueString($product[K::UniqueKey]) . ", ManufactureId = " . Db::getSQLValueString($product[K::ManufactureId], K::int) . ", Keyword = " . Db::getSQLValueString($product[K::Keyword]) . ", WarehouseUnit = " . Db::getSQLValueString($product[K::WarehouseUnit]) . ", ExchangeProduct = " . Db::getSQLValueString($product[K::ExchangeProduct]) . ", UnitRatio = " . Db::getSQLValueString($product[K::UnitRatio], K::double) . ", IsApprove = " . Db::getSQLValueString($product[K::IsApprove], K::int) . ", CreatorId = " . Db::getSQLValueString($product[K::CreatorId], K::int) . ", Created = " . Db::getSQLValueString($product[K::Created], K::date) . ", State = " . Db::getSQLValueString($product[K::State]) . ", MarketingPrice = " . Db::getSQLValueString($product[K::MarketingPrice]) . ", TypeCode = " . Db::getSQLValueString($product[K::TypeCode]) . ", Tax = " . Db::getSQLValueString($product[K::Tax]) . ", PictureId = " . Db::getSQLValueString($fileData[$productCode][K::name][K::PictureId]) . ", Site = " . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE)) . ", Price = " . Db::getSQLValueString($product[K::Price]) . ", Discount = 0 WHERE Code = " . Db::getSQLValueString($productCode) . " ")) {
                    throw new Exception(mysql_error());
                };
            }

            $currPictures = Db::g()->toArray("SELECT * FROM product_picture WHERE Product = " . Db::getSQLValueString($productCode) . " ", array(
                K::IndexColumns => K::Id
            ));

            foreach ($currPictures as $currPicId => $currPicture) {
                $flag = false;
                foreach ($product[K::Pictures] as $newPicId => $newPicture) {
                    if ($currPicId == $newPicture) {
                        $flag = true;
                        break;
                    }
                }
                if (!$flag) {
                    unlink(Config_Parameter::g(K::uploadDir) . '/' . $currPicture[K::FileId]);
                    Db::g()->query("DELETE FROM product_picture WHERE Id = " . Db::getSQLValueString($currPicId) . " ");
                }
            }

            foreach ($product[K::Pictures] as $newPicId => $newPicture) {
                $flag = false;
                foreach ($currPictures as $currPicId => $currPicture) {
                    if ($currPicId == $newPicture) {
                        $flag = true;
                        break;
                    }
                }

                if (!$flag) {
                    move_uploaded_file($fileData[$productCode][K::tmp_name][K::Pictures][$newPicId][K::FileId], Config_Parameter::g(K::uploadDir) . '/' . $fileData[$productCode][K::name][K::Pictures][$newPicId][K::FileId]);
                    Db::g()->query("INSERT INTO product_picture (Id, Product, Description, FileId) VALUES (" . Db::getSQLValueString($newPicture[K::Id], K::int) . "," . Db::getSQLValueString($newPicture[K::Product]) . "," . Db::getSQLValueString($newPicture[K::Description]) . "," . Db::getSQLValueString($fileData[$productCode][K::name][K::Pictures][$newPicId][K::FileId]) . ")");
                }

            }

            foreach ($product[K::Pictures] as $newPicId => $newPicture) {
                foreach ($currPictures as $currPicId => $currPicture) {
                    if ($currPicId == $newPicture) {
                        unlink(Config_Parameter::g(K::uploadDir) . '/' . $currPicture[K::FileId]);
                        move_uploaded_file($fileData[$productCode][K::tmp_name][K::Pictures][$newPicId][K::FileId], Config_Parameter::g(K::uploadDir) . '/' . $fileData[$productCode][K::name][K::Pictures][$newPicId][K::FileId]);
                        Db::g()->query("UPDATE product_picture SET Description = " . Db::getSQLValueString($newPicture[K::Description]) . ", FileId = " . Db::getSQLValueString($fileData[$productCode][K::name][K::Pictures][$newPicId][K::FileId]) . " WHERE Id = " . Db::getSQLValueString($currPicId) . " ");
                    }
                }
            }
        }

        return true;
    }

    public function updateProductCategories($postData, $fileData) {
        $presentList = array();
        Db::q("SET FOREIGN_KEY_CHECKS=0");
        foreach ($postData as $productCateCode => $productCate) {
            if (!($cate = Db::a("SELECT Code FROM product_category WHERE Code = " . Db::s($productCateCode) . " "))) {
                Db::q("INSERT INTO product_category (Site, Code, Name, Parent, ImageId, UniqueKey) VALUES (" . Db::s(Config_Parameter::g(K::SITE_CODE)) . "," . Db::s($productCate[K::Code]) . "," . Db::s($productCate[K::Name]) . "," . Db::s($productCate[K::Parent]) . ", " . Db::s($productCate[K::ImageId]) . ", " . Db::s($productCate[K::UniqueKey]) . ") ");
            } else {
                unlink(Config_Parameter::g(K::uploadDir) . '/' . $cate[K::ImageId]);
                if ($fileData[$productCateCode]) {
                    move_uploaded_file($fileData[$productCateCode][K::tmp_name][K::ImageId], Config_Parameter::g(K::uploadDir) . '/' . $fileData[$productCateCode][K::name][K::ImageId]);
                }
                Db::q("UPDATE product_category SET Site=" . Db::getSQLValueString(Config_Parameter::g(K::SITE_CODE)) . ", Code=" . Db::s($productCate[K::Code]) . ", Name=" . Db::s($productCate[K::Name]) . ", Parent=" . Db::s($productCate[K::Parent]) . ", ImageId=" . Db::s($fileData[$productCateCode][K::name][K::ImageId]) . " WHERE Code = " . Db::s($productCateCode) . " ");
            }
            $presentList[] = Db::s($productCateCode);
        }

        // Remove
        Db::q("DELETE FROM product_category WHERE Code NOT IN (" . (implode(',', $presentList) ?: '-1') . ")");
        Db::q("SET FOREIGN_KEY_CHECKS=1");

    }

    public function updatePostCategories($postData, $fileData) {
        $presentList = array();
        foreach ($postData as $postCateCode => $postCate) {
            if (!($cate = Db::a("SELECT Code FROM web_post_category WHERE Code = " . Db::s($postCateCode) . " "))) {
                Db::q("INSERT INTO web_post_category (Site, Parent, Code, Name) VALUES (" . Db::s(Config_Parameter::g(K::SITE_CODE)) . "," . Db::s($postCate[K::Parent]) . "," . Db::s($postCate[K::Code]) . ", " . Db::s($postCate[K::Name]) . ") ");
            } else {
                Db::q("UPDATE web_post_category SET Site=" . Db::s(Config_Parameter::g(K::SITE_CODE)) . ", Parent=" . Db::s($postCate[K::Parent]) . ", Code=" . Db::s($postCate[K::Code]) . ", Name=" . Db::s($postCate[K::Name]) . " WHERE Code = " . Db::s($postCateCode) . " ");
            }
            $presentList[] = Db::s($postCateCode);
        }

        // Remove
        Db::q("DELETE FROM web_post_category WHERE Code NOT IN (" . (implode(',', $presentList) ?: '-1') . ")");

    }

    public function updatePosts($postData, $fileData) {
//        print_r($postData);
        $presentList = array();
        foreach ($postData as $postCode => $post) {
            if (!($cate = Db::a($q = "SELECT Code FROM web_post WHERE Code = " . Db::s($postCode) . " "))) {
                Db::q($q = "INSERT INTO web_post (Site, Code , Category, Title, Content, Keyword,UniqueKey) VALUES (" . Db::s(Config_Parameter::g(K::SITE_CODE)) . "," . Db::s($post[K::Code]) . "," . Db::s($post[K::Category]) . ", " . Db::s($post[K::Title]) . ", " . Db::s($post[K::Content]) . ", " . Db::s($post[K::Keyword]) . ", " . Db::s($post[K::UniqueKey]) . ") ");
//                echo $q;
            } else {
                Db::q($q = "UPDATE web_post SET Site=" . Db::s(Config_Parameter::g(K::SITE_CODE)) . ", Category=" . Db::s($post[K::Category]) . ", Code=" . Db::s($post[K::Code]) . ", Title=" . Db::s($post[K::Title]) . ", Content=" . Db::s($post[K::Content]) . ", UniqueKey=" . Db::s($post[K::UniqueKey]) . " WHERE Code = " . Db::s($postCode) . " ");
//                echo $q;
            }
            $presentList[] = Db::s($postCode);
        }

        // Remove
        Db::q("DELETE FROM web_post WHERE Code NOT IN (" . (implode(',', $presentList) ?: '-1') . ")");

    }

    public function updateWidgets($postData, $fileData) {
        $presentList = array();
        try {
            foreach ($postData as $code => $widget) {
                Db::g()->insertOrUpdate('web_widget', 'Code', $widget);
//            if (!($cate = Db::a($q = "SELECT Code FROM web_widget WHERE Code = " . Db::s($code) . " "))) {
//                Db::q($q = "INSERT INTO web_widget (Site, Code, Title, Content) VALUES (" . Db::s(Config_Parameter::g(K::SITE_CODE)) . "," . Db::s($widget[K::Code]) . "," . Db::s($widget[K::Title]) . ", " . Db::s($widget[K::Content]) . ") ");
//            } else {
//                Db::q($q = "UPDATE web_widget SET Site=" . Db::s(Config_Parameter::g(K::SITE_CODE)) . ", Code=" . Db::s($widget[K::Code]) . ", Title=" . Db::s($widget[K::Title]) . ", Content=" . Db::s($widget[K::Content]) . " WHERE Code = " . Db::s($code) . " ");
//            }
                $presentList[] = Db::s($code);
            }

            // Remove
            Db::q("DELETE FROM web_widget WHERE Code NOT IN (" . (implode(',', $presentList) ?: '-1') . ")");
        } catch (Exception $e) {
            return false;
        }

    }

    public function updateProductInCategory($postData, $fileData) {
//        print_r($postData);
        $presentList = array();
        foreach ($postData as $productInCate) {
            if (!(Db::a($q = "SELECT Product FROM product_in_category WHERE Product = " . Db::s($productInCate[K::Product]) . " && Category = " . Db::s($productInCate[K::Category]) . " "))) {
//                echo $q;
                Db::q($q = "INSERT INTO product_in_category (Id, Product, Category) VALUES (" . Db::s($productInCate[K::Id]) . "," . Db::s($productInCate[K::Product]) . "," . Db::s($productInCate[K::Category]) . ") ");
//                echo $q;
            }
            $presentList[] = Db::s($productInCate[K::Id]);
        }

        // Remove
        Db::q($q = "DELETE FROM product_in_category WHERE Id NOT IN (" . (implode(',', $presentList) ?: '-1') . ")");
//        echo $q;
    }

} 