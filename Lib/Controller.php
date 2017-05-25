<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 22/3/2017
 * Time: 11:10 AM
 */
class Controller {

    protected static $self;
    protected $moduleName;
    protected $controllerName;
    protected $viewName;
    protected $workWithTemplate = true;
    protected $defaultTemplateDir;
    protected $defaultTemplatePath;
    protected $defaultTemplateIndex;
    protected $siteTitle;
    protected $siteKeyword;
    protected $siteDescription;
    protected $siteLogo;
    protected $siteName;
    protected $siteTopic;
    protected $siteType;

    /**
     * @var Bootstrap
     */
    protected $bootstrap;

    /**
     * @param Bootstrap $bootstrap
     */
    function __construct($bootstrap) {
        self::$self = $this;
        $this->bootstrap = $bootstrap;
        $this->defaultTemplateDir = Config_Parameter::g(K::DEFAULT_TEMPLATE_DIR);
        $this->defaultTemplatePath = Config_Parameter::g(K::DEFAULT_TEMPLATE_PATH);
        $this->defaultTemplateIndex = Config_Parameter::g(K::DEFAULT_TEMPLATE_INDEX);
        $this->moduleName = $bootstrap->getModuleName();
        $this->controllerName = $bootstrap->getControllerName();
        $this->viewName = $bootstrap->getViewName();
        $this->siteTitle = Config_Parameter::getSiteInfo(K::WEB_META_TITLE);
        $this->siteKeyword = Config_Parameter::getSiteInfo(K::WEB_META_KEYWORD);
        $this->siteDescription = Config_Parameter::getSiteInfo(K::WEB_META_DESCRIPTION);
        $this->siteLogo = Config_Parameter::g(K::uploadDir) . '/' . Config_Parameter::getSiteInfo(K::WEB_META_LOGO);
        $this->siteTopic = Config_Parameter::getSiteInfo(K::WEB_META_TOPIC);
        $this->siteType = Config_Parameter::getSiteInfo(K::WEB_META_TYPE);
        $this->siteName = Config_Parameter::getSiteInfo(K::WEB_META_NAME);
    }

    public static function g($bootstrap) {
        if (self::$self) {
            return self::$self;
        }
        return new self($bootstrap);
    }

    /**
     * @return mixed
     */
    public function getModuleName() {
        return $this->moduleName;
    }

    /**
     * @param mixed $moduleName
     * @return $this
     */
    public function setModuleName($moduleName) {
        $this->moduleName = $moduleName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getControllerName() {
        return $this->controllerName;
    }

    /**
     * @param mixed $controllerName
     * @return $this
     */
    public function setControllerName($controllerName) {
        $this->controllerName = $controllerName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getViewName() {
        return $this->viewName;
    }

    /**
     * @param mixed $viewName
     * @return $this
     */
    public function setViewName($viewName) {
        $this->viewName = $viewName;
        return $this;
    }

    /**
     *
     */
    public function onActionNotFound() {
    }

    /**
     * Render Template
     */
    public function renderTemplate() {
        if ($this->workWithTemplate) {
            include($this->getDefaultTemplate());
        }
    }

    /**
     * @return string
     */
    private function getDefaultTemplate() {
        return $this->defaultTemplateDir . '/' . $this->defaultTemplateIndex;
    }

    public function renderView($specialView = NULL, $option = array()) {
        if (preg_match('/\//', $specialView)) {
            $viewPath = $specialView;
        } else {
            $viewPath = $this->getModuleName() . '/' . $this->getControllerName() . '/' . ($specialView ?: $this->getViewName());
        }
        $viewOnTemplate = $this->defaultTemplateDir . '/View/' . $viewPath . '.php';
        if (file_exists($viewOnTemplate)) {
            include($viewOnTemplate);
            return true;
        }
        return false;
    }

    public function getDefaultTemplatePath() {
        return $this->defaultTemplatePath;
    }

    public function error404Action() {
        $this->setSiteTitle('404 Page Not Found - ' . Config_Parameter::getSiteInfo(K::WEB_META_TITLE));
    }

    /**
     * @return boolean
     */
    public function isWorkWithTemplate() {
        return $this->workWithTemplate;
    }

    /**
     * @param boolean $workWithTemplate
     * @return $this
     */
    public function setWorkWithTemplate($workWithTemplate) {
        $this->workWithTemplate = $workWithTemplate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteTitle() {
        return $this->siteTitle;
    }

    /**
     * @param mixed $title
     * @return $this
     */
    public function setSiteTitle($title) {
        $this->siteTitle = $title;
        return $this;
    }

    /**
     * @return Bootstrap
     */
    public function getBootstrap() {
        return $this->bootstrap;
    }

    /**
     * @param bool $flag
     * @return $this
     */
    public function setHeaderTypeAsJson($flag = true) {
        if ($flag) {
            header('Content-Type: application/json');
        } else {
            header('Content-Type: text/html; charset=utf-8');
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteKeyword() {
        return $this->siteKeyword;
    }

    /**
     * @param mixed $keyword
     * @return $this
     */
    public function setSiteKeyword($keyword) {
        $this->siteKeyword = $keyword;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteDescription() {
        return $this->siteDescription;
    }

    /**
     * @param mixed $description
     * @return $this
     */
    public function setSiteDescription($description) {
        $this->siteDescription = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteLogo() {
        return $this->siteLogo;
    }

    /**
     * @param mixed $siteLogo
     * @return $this
     */
    public function setSiteLogo($siteLogo) {
        $this->siteLogo = $siteLogo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteName() {
        return $this->siteName;
    }

    /**
     * @param mixed $siteName
     * @return $this
     */
    public function setSiteName($siteName) {
        $this->siteName = $siteName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteTopic() {
        return $this->siteTopic;
    }

    /**
     * @param mixed $siteTopic
     * @return $this
     */
    public function setSiteTopic($siteTopic) {
        $this->siteTopic = $siteTopic;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteType() {
        return $this->siteType;
    }

    /**
     * @param mixed $siteType
     * @return $this
     */
    public function setSiteType($siteType) {
        $this->siteType = $siteType;
        return $this;
    }

    protected function replaceByPageNotFound() {
        $this->setSiteTitle('404 Page Not Found - ' . $this->getSiteTitle());
        $this->viewName = 'error404';
        header('HTTP/1.0 404 Not Found');
    }

    public function contentFilter($content) {
        return $content;
    }

    /**
     * @param bool $skipNotification
     * @param null $errCode
     * @param Exception_Business $e
     * @return ArrayObject
     */
    public function makeErrorResponse($skipNotification = false, $errCode = null, $e = null) {
        return new ArrayObject(array(
            K::skipNotification => $skipNotification,
            K::return_KEY => false,
            K::errorCode => $errCode,
            K::logs => System::getErrorBusLogs(),
            K::SysLog => System::getLogs(),
            K::trace => Config_Parameter::g(K::IS_DEBUG_MODE) && $e instanceof Exception_Business ? $e->getTraceRecursive() : null
        ));
    }

    /**
     * @param bool $skipNotification
     * @param null $errCode
     * @param Exception_Business $e
     * @return string
     */
    public function makeErrorResponseAsJson($skipNotification = false, $errCode = null, Exception_Business $e = null) {
        return System_Json::encode($this->makeErrorResponse($skipNotification, $errCode, $e));
    }

    /**
     * @return ArrayObject
     */
    public function makeSuccessResponse() {
        return new ArrayObject(array(
            K::return_KEY => true,
        ));
    }

    /**
     * @return string
     */
    public function makeSuccessResponseAsJson() {
        return System_Json::encode($this->makeSuccessResponse());
    }
} 