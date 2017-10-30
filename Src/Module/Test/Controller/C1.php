<?php
/**
 * Created by PhpStorm.
 * User: hoangtriet
 * Date: 29/10/17
 * Time: 12:59 PM
 */

class Test_Controller_C1 extends Controller {

    function __construct(Bootstrap $bootstrap) {
        parent::__construct($bootstrap);
        $this->setWorkWithTemplate(false);
    }

    public static function g($bootstrap) {
        return new self($bootstrap);
    }

    public function checkProcessAction() {
        error_reporting(0);
        $pid = $this->getBootstrap()->getRequestParams('pid');
        $result = Common::checkProcessRunning($pid);
        echo "check pid => " . ($result ? 'true' : 'false') . "\n";
    }

    public function writePidToDiskAction() {
        file_put_contents(BASE_DIR . '/backup-run.pid', getmypid());
    }


}