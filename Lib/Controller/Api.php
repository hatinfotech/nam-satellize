<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 24/5/2017
 * Time: 15:02
 */
class Controller_Api extends Controller {

    /**
     * @param Bootstrap $bootstrap
     */
    function __construct($bootstrap) {
        parent::__construct($bootstrap);
        $this->setWorkWithTemplate(false);
    }

    /**
     * @param $bootstrap
     * @return Controller|Controller_Api
     */
    public static function g($bootstrap) {
        return new self($bootstrap);
    }

    protected function checkTokenKey($tokenKey) {
        return true;
    }

    public function requestAction() {
        $this->setWorkWithTemplate(false);
        $this->setHeaderTypeAsJson(true);
        try {
            $command = $this->getBootstrap()->getRequestParams('command');
            $tokenKey = $this->getBootstrap()->getRequestParams('tokenKey');

            if (!$this->checkTokenKey($tokenKey)) {
                throw new Exception_Business('Token key not match');
            }

            $get = $this->getBootstrap()->getRequestParams();
            $data = $this->getBootstrap()->getPostData();
            $file = $this->getBootstrap()->getFileData();

            if (!method_exists($this, $function = $command . 'Api')) {
                throw Exception_Business::g(sprintf(__trans('\'%s\' function was not defined'), $command))->setAutoTranslate(false);
            }

            $result = $this->{$function}($get, $data, $file);
            if ($result === false) {
                throw Exception_Business::g(sprintf(__trans('\'%s\' function result false'), $command))->setAutoTranslate(false);
            }

            $response = $this->makeSuccessResponse();
            $response[K::data] = $result;

            echo System_Json::encode($response);

            return true;
        } catch (Exception_Business $e) {
            System::busError($e->getMessage());
            $response = $this->makeErrorResponse(false, $e->getCode(), $e);
            echo System_Json::encode($response);
            return false;
        }

    }

} 