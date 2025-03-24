<?php

namespace App\Exception;

use Exception;
use Throwable;

class DataNotFoundException extends Exception
{
    public function __construct(
        string $message = "Data Requested Not Found",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
