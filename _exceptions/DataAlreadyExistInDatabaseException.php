<?php

/**
 * Data Already Exist In Database Exception File
 * Date: 05/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 */

namespace OpenBooking\_Exceptions;
use \Exception;

/**
 * Class DataAlreadyExistInDatabaseException
 * @package OpenBooking\_Exceptions
 */
class DataAlreadyExistInDatabaseException extends Exception
{
    /**
     * DataAlreadyExistInDatabaseException constructor.
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message , $code = -9, Exception $previous = null) {
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