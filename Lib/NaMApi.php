<?php

class NaMApi {

    private $url;
    protected $transApiPath = 'Transportation/WebApi/request';
    protected $salesApiPath = 'Sales/WebApi/request';
    protected $webAdminApi = 'WebAdmin/Api/request';
    protected $backupApiPath = 'Backup/Api/request';
    private $secureSalt;
    private $timeout = 300;

    function __construct($url = null, $secureSalt = null) {
        $this->url = $url ?: Config_Parameter::g(K::NAM_API_URl);
        $this->secureSalt = $secureSalt ?: Config_Parameter::g(K::SECURE_SALT);
        if (!$_SESSION[K::cookie]) {
            $_SESSION[K::cookie] = base64_encode(date('Ym-dHis'));
        }
    }

    /**
     * Get instance api
     * @param null $url
     * @param null $secureSalt
     * @return NaMApi
     */
    public static function g($url = null, $secureSalt = null) {
        return new self($url, $secureSalt);
    }

    protected function getTokenKey() {
        return md5($this->secureSalt . ((int)((time() / $this->timeout))));
    }

    /**
     * Request api
     * @param $cmd
     * @param $parameters
     * @param $data
     * @return array [return=>?,data=>?]
     */
    protected function requestTransApi($cmd, $parameters = array(), $data = array()) {
        return $this->requestApi($this->transApiPath, $cmd, $parameters, $data);
    }

    protected function requestSalesApi($cmd, $parameters = array(), $data = array()) {
        return $this->requestApi($this->salesApiPath, $cmd, $parameters, $data);
    }

    protected function requestWebAdminApi($cmd, $parameters = array(), $data = array()) {
        return $this->requestApi($this->webAdminApi, $cmd, $parameters, $data);
    }

    protected function requestBackupApi($cmd, $parameters = array(), $data = array()) {
        return $this->requestApi($this->backupApiPath, $cmd, $parameters, $data);
    }

    protected function requestApi($apiPath, $cmd, $parameters = array(), $data = array()) {
        //$formData = $this->convertToFormData($data);

        //Common::printArr($formData);

        $parameterStr = '';
        foreach ($parameters as $key => $parameter) {
            $parameterStr .= "&$key=$parameter";
        }


        $tokenKey = $this->getTokenKey();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, BASE_DIR . "/Certificate/subdomain.namsoftware.com.crt");

        $url = "{$this->url}/" . $apiPath . "?command={$cmd}&tokenKey={$tokenKey}{$parameterStr}";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: PHPSESSID=" . $_SESSION[K::cookie]));
        curl_setopt($ch, CURLOPT_POSTFIELDS, array_merge($this->convertToFormData($data), array(
            'TokenKey' => $tokenKey,
        )));
        $response = curl_exec($ch);
        //echo $response;
        //exit;
        curl_close($ch);
        $result = json_decode($response, true);
        if (!$result) {
            file_put_contents(BASE_DIR . '/tmp/api_response.txt', $url . "\n" . $response . "\n" . curl_error($ch) . "\n");
            echo $response;
            exit();
        }
        return $result;
    }

    protected function convertToFormData($data, &$dataPost = array(), $postKey = null) {
        foreach ($data as $key => $item) {
            if (is_array($item) || $item instanceof ArrayObject) {
                $this->convertToFormData($item, $dataPost, $postKey ? ($postKey . '[' . $key . ']') : ($key));
            } else {
                if ($postKey) {
                    $dataPost[$postKey . "[$key]"] = $item;
                } else {
                    $dataPost[$key] = $item;
                }
            }
        }

        return $dataPost;
    }

    /**
     * Create transport ticket
     * @param $data
     * @param $imageFile
     * @param $excelFile
     * @return array
     */
    public function createTicket($data, $imageFile, $excelFile) {
//        $tokenKey = $requestTokenKey = $this->getTokenKey();
        $onLevelData = array();
        foreach ($data as $item) {

        }


        $cExcelFile = NULL;
        $cImageFile = NULL;
        if (is_file($excelFile)) {
            if (function_exists('curl_file_create')) { // php 5.6+
                $cExcelFile = curl_file_create($excelFile);
            } else { //
                $cExcelFile = '@' . realpath($excelFile);
            }
        }

        if (is_file($imageFile)) {
            if (function_exists('curl_file_create')) { // php 5.6+
                $cImageFile = curl_file_create($imageFile);
            } else { //
                $cImageFile = '@' . realpath($imageFile);
            }
        }

        $response = $this->requestTransApi('createTicket', null, array_merge($data, array(
            'ExcelFile' => $cExcelFile,
            'GoodsImage' => $cImageFile
        )));
        return $response;
    }

    public function login($username, $password, $remember = false) {
        $result = $this->requestTransApi('login', array(K::username => $username, K::password => $this->secureEncrypt($password), K::remember => $remember));
        if ($result) {
            //
        }
        return $result;
    }

    /**
     * Register contact
     * @param $data [Name, Phone, Email, Address, Password]
     * @return array
     */
    public function register($data) {
        $result = $this->requestTransApi('register', null, $data);
        if ($result) {
            //
        }
        return $result;
    }

    public function logout() {
        $result = $this->requestTransApi('logout');
        if ($result) {
            //
        }
        return $result;
    }

    protected function secureEncrypt($str) {
        return $str;
    }

    public function getTicketList($limit, $page) {
        return $this->requestTransApi('getTicketList', array(K::limit => $limit, K::page => $page));
    }

    public function getTicketInfo($ticketCode) {
        return $this->requestTransApi('getTicketInfo', array(K::ticket => $ticketCode));
    }

    public function cancelTicketByCustomer($ticketCode) {
        return $this->requestTransApi('cancelTicketByCustomer', array(K::ticket => $ticketCode));
    }

    public function getSuggestPrices($locationFrom, $locationTo, $weight, $isCashOnDelivery, $cashOnDelivery, $isAdvancePayment, $advancePayment) {
        return $this->requestTransApi('getSuggestPrices', array(
            K::locationFrom => $locationFrom,
            K::locationTo => $locationTo,
            K::weight => $weight,
            K::isCashOnDelivery => $isCashOnDelivery,
            K::cashOnDelivery => $cashOnDelivery,
            K::isAdvancePayment => $isAdvancePayment,
            K::advancePayment => $advancePayment,
        ));
    }

    public function sendContact($data) {
        return $this->requestWebAdminApi('sendContact', null, $data);
    }

    public function order($data) {
        return $this->requestSalesApi('order', null, $data);
    }

    public function getBackupPlane($code) {
        return $this->requestBackupApi('getBackupPlane', array(
            'plane' => $code
        ));
    }

    public function updateBackupScheduleToRunningState($id) {
        return $this->requestBackupApi('updateBackupScheduleToRunningState', array(
            'schedule' => $id
        ));
    }

    public function updateBackupScheduleToReadyState($id) {
        return $this->requestBackupApi('updateBackupScheduleToReadyState', array(
            'schedule' => $id
        ));
    }

    public function writeBackupHistory($plane, $locationName, $backupFileName, $status) {
        return $this->requestBackupApi('writeBackupHistory', array(
            'plane' => $plane,
            'locationName' => $locationName,
            'backupFileName' => $backupFileName,
            'status' => $status
        ));
    }

    public function updateLocationBackingUp($scheduleId, $locationId, $backupFileName) {
        return $this->requestBackupApi('updateLocationBackingUp', array(
            'schedule' => $scheduleId,
            'locationId' => $locationId,
            'backupFileName' => $backupFileName,
        ));
    }

    public function updateLocationLastRunningStateAsFailed($locationId, $fileName) {
        return $this->requestBackupApi('updateLocationLastRunningStateAsFailed', array(
            'location' => $locationId,
            'fileName' => $fileName,
        ));
    }

    public function updateLocationLastRunningStateAsSuccess($locationId, $fileName) {
        return $this->requestBackupApi('updateLocationLastRunningStateAsSuccess', array(
            'location' => $locationId,
            'fileName' => $fileName,
        ));
    }

    public function updateLiveStatus($plane) {
        return $this->requestBackupApi('updateLiveStatus', array(
            'plane' => $plane,
        ));
    }

    public function getBackupFileSize($backupFile) {
        return $this->requestBackupApi('getBackupFileSize', array(
            'backupFile' => $backupFile,
        ));
    }

}