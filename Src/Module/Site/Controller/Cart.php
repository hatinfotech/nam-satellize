<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/5/2017
 * Time: 21:20
 */
class Site_Controller_Cart extends Site_Controller_Main {

    function __construct($bootstrap) {
        parent::__construct($bootstrap);
        if (!$_SESSION[K::cart]) {
            $_SESSION[K::cart] = array(
                K::productList => array()
            );
        }
    }

    public function mainAction() {

    }

    public function addToCartAction() {
        $code = $this->getBootstrap()->getRequestParams('product');
        print_r($code);
        $product = Db::f("SELECT * FROM product WHERE Code = " . Db::getSQLValueString($code));
        if ($product) {
            if (array_key_exists($product[K::Code], $_SESSION[K::cart][K::productList])) {
                $_SESSION[K::cart][K::productList][$product[K::Code]]['Quantity']++;
            } else {
                $_SESSION[K::cart][K::productList][$product[K::Code]] = $product;
                $_SESSION[K::cart][K::productList][$product[K::Code]]['Quantity'] = 1;
            }
        }
        Common::redirect('/site/cart/main');
    }

    public function removeProductAction() {
        $code = $this->getBootstrap()->getRequestParams('product');
        unset($_SESSION[K::cart][K::productList][$code]);
        Common::redirect('/site/cart/main');
    }

    protected function destroy() {
        $_SESSION[K::cart] = array(
            K::productList => array()
        );
        return true;
    }

    public function destroyAction() {
        $this->destroy();
        Common::redirect('/site/cart/main');
    }

    public function orderAction() {
        $productList = array();
        foreach ($_SESSION[K::cart][K::productList] as $code => $p) {
            $productList[$code] = array(
                K::Code => $p[K::Code],
                K::Name => $p[K::Name],
                K::Quantity => $p[K::Quantity],
                K::Price => $p[K::Price]
            );
        }

        $response = NaMApi::g()->order(array(
            K::contact => $_POST,
            K::productList => $productList
        ));

        $this->checkApiResponse($response);
        //Common::redirect('/site/cart/cartInfo?code=' . $response[K::data]);
        //Common::printArr($response);
        $this->destroy();
        Common::notify("Đơn hàng đã được ghi nhận, chúng tôi sẽ gọi lại cho bạn trong thời gian sớm nhất !");
        //exit;
        return true;
    }
} 