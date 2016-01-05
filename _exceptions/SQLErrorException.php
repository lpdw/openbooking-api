<?php

/**
 * Date: 05/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 */

namespace OpenBooking\Exceptions;
use \Exception;

/**
 * Class SQLErrorException
 * @package OpenBooking\Exceptions
 */

class SQLErrorException extends Exception
{
    public function __construct($message = "SQL Error", $code = -6, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}