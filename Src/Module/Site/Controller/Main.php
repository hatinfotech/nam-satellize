<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 11:19 AM
 */
class Site_Controller_Main extends Controller {

    public $post;
    public $productCate;
    public $productInCateNumPerPage;
    public $productInCatePage;
    public $productInCateTotalPage;
    public $productCateUniqueKey;

    function __construct($bootstrap) {
        parent::__construct($bootstrap);
        //$this->productCate = Db::a("SELECT * FROM product_category ORDER BY Name");
        $this->generateCategoryTree($this->productCate);
        //Common::printArr($this->productCate);
    }

    /**
     * @param $bootstrap Bootstrap
     * @return Site_Controller_Main
     */
    public static function g($bootstrap) {
        return new self($bootstrap);
    }

    public function indexAction() {
    }

    protected function generateCategoryTree(&$tree, $parent = 'ROOT') {
        $tree['branches'] = Db::a("SELECT * FROM product_category WHERE Parent = " . Db::s($parent) . " ORDER BY Name");
        foreach ($tree[K::branches] as &$branch) {
            $this->generateCategoryTree($branch, $branch[K::Code]);
        }
    }

    protected function checkApiResponse($response) {
        if (!$response) {
            throw new Exception('NaM Api incorrect response');
        }
        if (!$response[K::returnKey]) {
            if ($response[K::errorCode] == C::NOT_LOGGED_IN_303) {
                // api was not togged in
                Common::requireLogin($response[K::logs][0] ?: 'Yêu cầu đăng nhập', $_SERVER[K::REDIRECT_URL]);
                return false;
            }
            throw new Exception($response[K::logs][0]);
        }
        return true;
    }

    /**
     *
     */
    public function sendTransTicketRequestAction() {
        if ($_POST['SendTicketRequest']) {
            try {
                $excelFile = BASE_DIR . '/tmp/' . $_FILES['ExcelFile']['name'];
                $goodsImage = BASE_DIR . '/tmp/' . $_FILES['GoodsImage']['name'];
                if ($_FILES['ExcelFile']) {
                    move_uploaded_file($_FILES['ExcelFile']['tmp_name'], $excelFile);
                }
                if ($_FILES['GoodsImage']) {
                    move_uploaded_file($_FILES['GoodsImage']['tmp_name'], $goodsImage);
                }

                $namApi = new NaMApi();
                //Common::printArr($_POST);
                //exit;
                $response = $namApi->createTicket($_POST, $goodsImage, $excelFile);
                $this->checkApiResponse($response);

                if (!$response[K::returnKey]) {
                    throw new Exception('Không thể tạo yêu cầu vận chuyển');
                }


                $_SESSION[K::LAST_NOTIFICATION] = 'Yêu cầu vận chuyển của bạn đã được ghi nhận, chúng tôi sẽ liên hệ lại trong thời gian sớm nhất.<br><a href="/danh-sach-van-don.html">Trở về danh sách vận đơn</a></a>';
                $_SESSION[K::LAST_NEW_TICKET] = $response[K::data][K::newTicketCode];
                header('Location: /thong-bao.html');

            } catch (Exception $e) {
                $_SESSION['LAST_EXCEPTION'] = $e;
                header('Location: /thong-bao.html');
            }
        }
    }

    public function notificationAction() {
        //echo 123;
        //debug_print_backtrace();
        //exit;
    }

    public function infoAction() {

    }

    public function supportAction() {

    }

    public function contactAction() {

    }

    public function articleAction() {
        $uniqueKey = $this->getBootstrap()->getRequestParams('UniqueKey');
        $this->post = Db::f($q = "SELECT * FROM web_post WHERE UniqueKey = " . Db::s($uniqueKey) . " ");
        if ($this->post) {
            $this->setSiteTitle($this->post[K::Title] . ' - ' . $this->getSiteTitle());
        } else {
            $this->replaceByPageNotFound();
        }

    }


    public function articleCategoryAction() {

    }

    public $productInfo;

    public function productAction() {
        $uniqueKey = $this->getBootstrap()->getRequestParams('uniqueKey');
        $this->productInfo = Db::f("SELECT * FROM product WHERE UniqueKey = " . Db::getSQLValueString($uniqueKey));
        return true;
    }

    public $productList;

    public function productCategoryAction() {
        $this->productCateUniqueKey = $this->getBootstrap()->getRequestParams('cateUniqueKey');
        $this->productInCateNumPerPage = 20;
        $this->productInCatePage = $this->getBootstrap()->getRequestParams('page');
        if (!$this->productInCatePage) {
            $this->productInCatePage = 1;
        }
        $offset = ($this->productInCatePage - 1) * $this->productInCateNumPerPage;
        $totalProduct = Db::f("SELECT count(*) AS TotalProduct FROM product p LEFT JOIN product_in_category ic ON p.Code = ic.Product LEFT JOIN product_category c ON ic.Category = c.Code WHERE c.UniqueKey = " . Db::getSQLValueString($this->productCateUniqueKey));
        $this->productInCateTotalPage = ceil($totalProduct['TotalProduct'] / $this->productInCateNumPerPage);
        //echo "\$numPerPage = $this->productInCateNumPerPage\n\$page=$this->productInCatePage\n\$offset=$offset\n\$totalPage=$this->productInCateTotalPage";

        $this->productList = Db::a("SELECT p.* FROM product p LEFT JOIN product_in_category ic ON p.Code = ic.Product LEFT JOIN product_category c ON ic.Category = c.Code WHERE c.UniqueKey = " . Db::getSQLValueString($this->productCateUniqueKey) . " limit $offset, $this->productInCateNumPerPage");
        return true;
    }

    public $widget;

    public function renderWidget($widgetCode, $template = C::bodyWidget) {
        $this->widget = Common::getWidget($widgetCode);
        $this->renderView('Widget/' . $template);
    }

    public function apiAction() {
        $this->setHeaderTypeAsJson(true);
        $this->workWithTemplate = false;

        $api = new WebApiServer();
        $result = $api->post($_GET, $_POST, $_FILES);
        echo json_encode($result);
        return true;
    }

    public function contentFilter($content) {
//        echo $content;
        ob_start();
        $this->renderView('productCate');
        $productCateHtml = ob_get_clean();
        $content = str_replace(array(
            '{VAR#PRODUCT_CATE}',
        ), array(
            $productCateHtml,
        ), $content);
        return $content;
    }

    public function getLocationByParentAction() {
        $this->setWorkWithTemplate(false);
        $this->setHeaderTypeAsJson(true);
        $parent = $_GET['parent'];
        if (!$parent) {
            throw new Exception('no enough condition for get location list');
        }
        $locationList = Common::getBusinessLocationsByParent($parent);
        //$locationList = Db::a("SELECT Code,FullName,ShortName FROM location WHERE Parent = " . Db::s($parent) . " && (Code IN (SELECT District FROM trans_business_district) || Code IN (SELECT Province FROM trans_business_district) || Code IN (SELECT Location FROM trans_business_location)) ORDER BY FullName ASC", array(K::IndexColumns => K::Code));
        echo json_encode(array(
            K::returnKey => true,
            K::data => $locationList
        ));
        return true;
    }

    public function testAction() {
        $this->setWorkWithTemplate(false);
        $this->renderView();
    }
} 