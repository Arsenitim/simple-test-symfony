<?php

namespace App\Exception;

use Exception;
use Throwable;

class InputParametersIncorrectException extends Exception
{
    public function __construct(
        string $message = "Input Parameters Are Wrong",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
