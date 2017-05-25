<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 11:10 AM
 */
class Bootstrap {

    protected $workingMod;
    protected $routeInfo;
    protected $moduleName;
    protected $controllerName;
    protected $viewName;
    protected $requestParams;
    protected $postData;
    protected $fileData;
    /**
     * @var Bootstrap
     */
    protected static $self;


    function __construct() {
        session_start();
    }

    /**
     * Get instance
     * @return Bootstrap
     */
    public static function g() {
        return self::$self ?: (self::$self = new self());
    }

    public function run() {
        try {
            $requestPath = $_SERVER[K::REQUEST_URI];
            $requestPath = explode('?', $requestPath);
            $requestPath = $requestPath[0];

            $this->routeInfo = $this->extractRequest($requestPath);

            /*** Auto load controller*/
            $this->moduleName = $this->routeInfo[K::map][0];
            $this->controllerName = $this->routeInfo[K::map][1];
            $this->viewName = $this->routeInfo[K::map][2];

            if (is_array($this->routeInfo[K::parameter])) {
                $this->requestParams = $this->routeInfo[K::parameter];
            }

            $this->requestParams = array_merge($this->requestParams, $_GET);
            $this->setPostData($_POST);
            $this->setFileData($_FILES);

            $controllerClassName = $this->moduleName . '_' . C::Controller . '_' . $this->controllerName;
            if (!class_exists($controllerClassName)) {
                if (Config_Parameter::g(K::IS_DEBUG_MODE)) {
                    echo "Class '{$controllerClassName}' was not exists !!!";
                }
            }
            if (!$this->moduleName || !$this->controllerName || !class_exists($controllerClassName)) {
                $controllerClassName = 'Site_Controller_Main';
                $this->moduleName = 'Site';
                $this->controllerName = 'Main';
                $this->viewName = 'error404';
                header('HTTP/1.0 404 Not Found');
            }

            /** @var Controller $controller */
            $controller = new $controllerClassName($this);

            if (!$controller) {
                Common::error404Redirect();
            }
            $actionName = $controller->getViewName() . C::Action;
            if (!method_exists($controller, $actionName)) {
                $controller->onActionNotFound();
            }
            /** @var Controller $controller */
            $controller->{$controller->getViewName() . C::Action}();
            if ($controller->isWorkWithTemplate()) {
                $controller->renderTemplate();
            }
        } catch (Exception $e) {
            //echo $e->getMessage();
            Common::errorReport($e);
            //exit;
        }

    }

    /**
     * @param $requestStr
     * @return array
     */
    public function extractRequest($requestStr) {
        if (Config_Parameter::g(K::BASE_PATH) != '') {
            $requestStr = str_replace(Config_Parameter::g(K::BASE_PATH) . '/', '', $requestStr);
        }
        $requestStr = trim($requestStr, '/');
        foreach (Config_Parameter::g(K::ROUTE) as $pattern => $map) {
            if (preg_match($pattern, $requestStr, $arrMatch)) {
                $param = array();
                for ($i = 1; $i < count($arrMatch); $i++) {
                    $param[$map[K::parameter][$i - 1]] = $arrMatch[$i];
                }
                return array(
                    K::parameter => $param,
                    K::map => $map
                );
            }
        }

        $defaultRequestStr = trim($requestStr, '/');
        if ($defaultRequestStr == null) {
            return false;
        }
        $defaultRequestStrPart = explode('/', $defaultRequestStr);
        $map = array();
        if ($defaultRequestStrPart[0] != null) {
            $map[0] = $defaultRequestStrPart[0];
            $map[0][0] = strtoupper($map[0][0]);
        } else {
            return false;
        }

        if ($defaultRequestStrPart[1] != null) {
            $map[1] = $defaultRequestStrPart[1];
            $map[1][0] = strtoupper($map[1][0]);
        } else {
            return false;
        }

        if ($defaultRequestStrPart[2] != null) {
            $map[2] = $defaultRequestStrPart[2];
            $map[2][0] = strtolower($map[2][0]);
        } else {
            return false;
        }

        for ($i = 3; $i < 10; $i++) {
            if (array_key_exists($i, $defaultRequestStrPart) && $defaultRequestStrPart[$i]) {
                $map[$i] = $defaultRequestStrPart[$i];
            }
        }

        return array(
            K::parameter => array(),
            K::map => $map
        );
    }

    /**
     * @param $post
     * @return $this
     */
    private function setPostData($post) {
        $this->postData = $post;
        return $this;
    }

    /**
     * @param $files
     * @return $this
     */
    private function setFileData($files) {
        $this->fileData = $files;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getModuleName() {
        return $this->moduleName;
    }

    /**
     * @return mixed
     */
    public function getControllerName() {
        return $this->controllerName;
    }

    /**
     * @return mixed
     */
    public function getViewName() {
        return $this->viewName;
    }

    /**
     * @return mixed
     */
    public function getRequestParams($key = NULL) {
        return $key ? $this->requestParams[$key] : $this->requestParams;
    }

    public function getPostData() {
        return $_POST;
    }

    public function getFileData() {
        return $_FILES;
    }

} 