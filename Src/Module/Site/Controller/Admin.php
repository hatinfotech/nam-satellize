<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 11:19 AM
 */
class Site_Controller_Admin extends Site_Controller_Main {

    public $post;
    //public $productCate;
    public $requestPage;
    public $ticket;
    public $ticketList;
    public $totalPage;
    public $currentPage;
    public $requireLoginMessage;
    public $loginBack;
    public $prevTicketListPage;
    public $errorMessage;

    function __construct($bootstrap) {
        parent::__construct($bootstrap);
        //$this->productCate = Db::a("SELECT * FROM product_category ORDER BY Name");
    }

    /**
     * @param $bootstrap Bootstrap
     * @return Site_Controller_Admin
     */
    public static function g($bootstrap) {
        return new self($bootstrap);
    }

    public function indexAction() {

    }

    public function ticketListAction() {
        $this->requestPage = $this->getBootstrap()->getRequestParams('page') ?: 1;
        //echo $_SESSION[K::TICKET_LIST_CURRENT_PAGE];
        $response = NaMApi::g()->getTicketList(20, $this->requestPage);
        $this->checkApiResponse($response);
        $this->ticketList = $response[K::data][K::listKey];
        $this->totalPage = $response[K::data][K::paging][K::totalPage];
        $this->currentPage = $response[K::data][K::paging][K::page];
        $_SESSION[K::TICKET_LIST_CURRENT_PAGE] = $this->currentPage;
    }

    public function ticketInfoAction() {
        $this->prevTicketListPage = $_SESSION[K::TICKET_LIST_CURRENT_PAGE] ?: 1;
        $code = $this->getBootstrap()->getRequestParams('ticket');
        $response = NaMApi::g()->getTicketInfo($code);
        $this->checkApiResponse($response);
        $this->ticket = $response[K::data];
    }

    public function loginAction() {
        if ($_SESSION[K::LAST_NOTIFICATION]) {
            $this->requireLoginMessage = $_SESSION[K::LAST_NOTIFICATION];
            //$_SESSION[K::LAST_NOTIFICATION] = null;
        }
        if ($this->getBootstrap()->getRequestParams('back')) {
            $_SESSION[K::LOGIN_BACK] = $this->getBootstrap()->getRequestParams('back');
        }
        if ($_POST['Submit']) {
            $username = $_POST['Username'];
            $password = $_POST['Password'];

            if (!$username || !$password) {
                Common::errorReport('Username hoặc mật khẩu rổng');
            }

            $namApi = new NaMApi();
            $response = $namApi->login($_POST[K::Username], $_POST[K::Password]);
            $this->checkApiResponse($response);
            $_SESSION[K::USER_INFO] = $response[K::data];
            $back = $_SESSION[K::LOGIN_BACK];
            $_SESSION[K::LOGIN_BACK] = null;
            Common::redirect($back ? urldecode($back) : '/Site/Admin/index');
        }
        return true;
    }

    public function registerAction() {
        if ($_SESSION[K::USER_INFO]) {
            Common::notify('Bạn đã đăng ký rồi !');
            return false;
        }
        include('simple-php-captcha/simple-php-captcha.php');
        //print_r($_SESSION['captcha']);
        if ($_POST['Submit']) {

            try {
                if (strtolower($_SESSION['captcha']['code']) != strtolower($_POST['Captcha'])) {
                    throw new Exception('Mã bảo vệ chưa khớp');
                }

                //Common::printArr($_POST);
                //exit;

                if (!$_POST[K::Name] || !$_POST[K::Phone] || !$_POST[K::Password] || !$_POST[K::ReTypePassword]) {
                    throw new Exception('Bạn hãy điền đầy đủ các trường bắt buộc');
                }

                if ($_POST[K::Password] != $_POST[K::ReTypePassword]) {
                    throw new Exception('Nhập lại mật khẩu chưa khớp');
                }

                $namApi = new NaMApi();
                $response = $namApi->register($_POST);
                $this->checkApiResponse($response);
                unset($_POST);
                Common::notify('Chúc mừng bạn đã đăng ký thành công<br><a href="/dang-nhap.html">Đăng nhập ngay</a>');
            } catch (Exception $e) {
                $this->errorMessage = $e->getMessage();
            }
        }
        $_SESSION['captcha'] = simple_php_captcha();
    }

    public function logoutAction() {
        $namApi = new NaMApi();
        $response = $namApi->logout();
        $this->checkApiResponse($response);
        unset($_SESSION[K::USER_INFO]);
        Common::redirect('/');
        return true;
    }

    public function getSuggestPricesAction() {
        $this->setWorkWithTemplate(false);
        $this->setHeaderTypeAsJson(true);
        $locationFrom = $this->getBootstrap()->getRequestParams('locationFrom');
        $locationTo = $this->getBootstrap()->getRequestParams('locationTo');
        $weight = $this->getBootstrap()->getRequestParams('weight');
        $isCodOrAdvancePayment = $this->getBootstrap()->getRequestParams('isCodOrAdvancePayment');
        $cashOnDelivery = $this->getBootstrap()->getRequestParams('cashOnDelivery');
        $advancePayment = $this->getBootstrap()->getRequestParams('advancePayment');
        if (!$locationFrom || !$locationTo) {
            echo('Not enough condition');
            return false;

        }
        $namApi = new NaMApi();
        $response = $namApi->getSuggestPrices($locationFrom, $locationTo, $weight, $isCodOrAdvancePayment == 'IsCashOnDelivery', $cashOnDelivery, $isCodOrAdvancePayment == 'IsAdvancePayment', $advancePayment);
        $this->checkApiResponse($response);

        echo json_encode($response[K::data]);
        return true;
    }

    public function cancelTicketAction() {
        $this->setWorkWithTemplate(false);
        $this->setHeaderTypeAsJson(true);

        $ticket = $this->getBootstrap()->getRequestParams('ticket');
        if (!$ticket) {
            throw new Exception_Business('Ticket code was not provide');
        }

        $namApi = new NaMApi();
        $response = $namApi->cancelTicketByCustomer($ticket);
        $this->checkApiResponse($response);
        Common::notify('Vận đơn đã được hủy theo yêu cầu của bạn !');
        return true;
    }
} 