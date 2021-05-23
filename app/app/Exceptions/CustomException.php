<?php

namespace App\Exceptions;
use Exception;

class CustomException extends Exception
{
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }
}
