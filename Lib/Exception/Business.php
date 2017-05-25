<?php

/**
 * Created by PhpStorm.
 * User: hatmt
 * Date: 2/8/2016
 * Time: 11:19 AM
 */
class Exception_Business extends Exception {

    public function __construct($message = "", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @var bool
     */
    protected $autoTranslate = true;

    /**
     * @param string $message
     * @param int $code
     * @param Exception $previous
     * @return \Exception_Business
     */
    public static function g($message = "", $code = 0, Exception $previous = null) {
        return new self($message, $code, $previous);
    }

    public static function dynamic($message = "", $variables, $code = 0, Exception $previous = null) {
        $exception = new self(vsprintf(__trans($message), $variables), $code, $previous);
        return $exception->setAutoTranslate(false);
    }

    /**
     * Prettify error message output
     * @return string
     */
    public function errorMessage() {
        $errorMsg = $this->getMessage();
        System::busAndLogError($errorMsg, $this->getFile(), $this->getLine(), $this->autoTranslate);
        return $errorMsg;
    }

    /**
     * @param boolean $autoTranslate
     * @return $this
     */
    public function setAutoTranslate($autoTranslate) {
        $this->autoTranslate = $autoTranslate;
        return $this;
    }

    public function getTranceAsStringRecursive() {
        /** @var Exception_Business $previous */
        $previous = $this->getPrevious();
        if ($previous instanceof Exception_Business) {
            return $previous->getTranceAsStringRecursive() . "\nError code : {$this->getCode()}\n" . $this->getTraceAsString();
        }
        return "\nError code : {$this->getCode()}\n" . $this->getTraceAsString();
    }

    public function getTraceRecursive() {
        /** @var Exception_Business $previous */
        $previous = $this->getPrevious();
        $result = [];
        if ($previous) {
            if ($previous instanceof Exception_Business) {
                $result = $previous->getTraceRecursive();
            } else {
                $result = $previous->getTrace();
            }
        } else {
            $result[] = str_replace(BASE_DIR, '', $this->getFile()) . "({$this->getLine()}): {$this->getMessage()}";
            $traces = $this->getTrace();
            foreach ($traces as $trace) {
                $argStr = '';
                foreach ($trace['args'] as $arg) {
                    if ($arg instanceof ArrayObject) {
                        $argStr .= get_class($arg) . ", ";
                    } else {
                        $argStr .= "[" . get_class($arg) . "] $arg, ";
                    }
                }
                $argStr = trim($argStr, ', ');
                $result[] = str_replace(BASE_DIR, '', $trace['file']) . "({$trace['line']}): {$trace['class']}->{$trace['function']}({$argStr})";
            }
        }
        return $result;
    }

} 