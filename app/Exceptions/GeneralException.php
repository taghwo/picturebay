<?php

namespace App\Exceptions;

use Exception;

class GeneralException extends Exception
{
    public $message;
    public $code;
    public function __construct($msg, $code=417)
    {
        $this->message = $msg;
        $this->code = $code;
    }

    public function render()
    {
        return response()->json([
            'status' => 'failed',
            'message' =>  $this->message
        ], $this->code);
    }
}
