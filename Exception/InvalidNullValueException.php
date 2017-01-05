<?php

namespace Bilendi\DevExpressBundle\Exception;

class InvalidNullValueException extends \RuntimeException
{
    public function __construct($operator)
    {
        $message = "Invalid null value for operator : `{$operator}`";
        parent::__construct($message);
    }
}
