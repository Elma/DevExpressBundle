<?php

namespace Bilendi\DevExpressBundle\Exception;

class UnknownCompositeException extends \RuntimeException
{
    public function __construct($composite)
    {
        $message = "Unknown composite expression `{$composite}`";
        parent::__construct($message);
    }
}
