<?php

/**
 * Date: 05/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 */

namespace OpenBooking\Exceptions;
use \Exception;

/**
 * Class ValidDatasException
 * @package OpenBooking\Exceptions
 */

class ValidDatasException extends Exception
{
    /**
     * ValidDatasException constructor.
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message , $code = -5, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Magic Method
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}