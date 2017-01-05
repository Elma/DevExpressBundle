<?php

namespace Bilendi\DevExpressBundle\Exception;

class NotNumericException extends \Exception
{
    public function __construct($field)
    {
        $message = "Value of field `{$field}` is not a numerical type";
        parent::__construct($message);
    }
}
