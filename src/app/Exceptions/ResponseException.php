<?php

namespace App\Exceptions;

use Exception;

class ResponseException extends Exception
{
    public function __construct($message = "Resource not found")
    {
        parent::__construct($message);
    }

    public function render($request)
    {
        return response()->json([
            'error' => $this->getMessage(),
        ], 404);
    }
}
