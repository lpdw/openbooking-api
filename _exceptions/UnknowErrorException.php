<?php

/**
 * Date: 05/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 */

namespace OpenBooking\Exceptions;
use \Exception;

/**
 * Class UnknowError
 * @package OpenBooking\Exceptions
 */

class UnknowErrorException extends Exception
{
    /**
     * UnknowErrorException constructor.
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "Unknow error, please try again ", $code = -4, Exception $previous = null) {
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