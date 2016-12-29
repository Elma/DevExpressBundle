<?php

namespace Bilendi\DevExpressBundle\Exception;


class UnknownComparisonException extends \RuntimeException
{
    public function __construct($operator) {
        $message = "Unknown comparision with operator `{$operator}`";
        parent::__construct($message);
    }
}