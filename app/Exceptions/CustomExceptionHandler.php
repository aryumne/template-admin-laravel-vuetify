<?php

namespace App\Exceptions;

use Exception;

class CustomExceptionHandler extends Exception
{
    protected $codeStatus;

    public function __construct($message, $codeStatus = 400)
    {
        parent::__construct($message);
        $this->codeStatus = $codeStatus;
    }

    public function getCodeStatus()
    {
        return $this->codeStatus;
    }
}
