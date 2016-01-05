<?php

/**
 * Unknow email template Exception
 * Date: 05/01/2016
 * Project: OpenBooking
 * @copyright 2015 - 2016 OpenBooking Group
 */

namespace OpenBooking\_Exceptions;
use \Exception;

/**
 * Class UnknowEmailTemplateException
 * @package OpenBooking\Exceptions
 */

class UnknowEmailTemplateException extends Exception
{
    /**
     * UnknowEmailTemplateException constructor.
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "Specified template is unknow. Can't send email", $code = -8, Exception $previous = null) {
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