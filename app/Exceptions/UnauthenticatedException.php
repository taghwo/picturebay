<?php

namespace App\Exceptions;

use Exception;

class UnauthenticatedException extends Exception
{
    protected $msg;
    protected $code;
    public function __construct($msg,$code=401)
    {
        $this->msg = $msg;
        $this->code = $code;
    }

    public function render()
    {
        return response()->json([
            'status' => 'failed',
            'message' =>  $this->msg
        ],  $this->code);
    }
}
