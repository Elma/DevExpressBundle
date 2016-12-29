<?php

namespace Bilendi\DevExpressBundle\Exception;


class NotArrayException extends \Exception
{
    public function __construct($field) {
        $message = "Value of field `{$field}` is not an array type";
        parent::__construct($message);
    }
}