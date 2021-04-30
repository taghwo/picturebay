<?php

namespace App\Exceptions;

use Exception;

class UnauthorisedException extends Exception
{
    protected $msg;
    public function __construct($msg)
    {
        $this->msg = $msg;
    }

    public function render()
    {
        return response()->json([
            'status' => 'failed',
            'message' =>  $this->msg
        ], 403);
    }
}
