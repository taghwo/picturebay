<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function respondWithSuccess($message, $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message
        ], $code);
    }

    public function respondSuccessWithData($data, $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], $code);
    }

    public function respondWithPaginatedData($data, $code = 200)
    {
        return response()->json($data, $code);
    }

    public function respondErrorWithMessage($message = '', $code = 417)
    {
        $msg = empty($message)? 'sorry the server could not handle the request, try again later':$message;
        return response()->json([
            'status' => 'failed',
            'message' => $msg
        ], $code);
    }

    public function respondModelNotFoundError($model)
    {
        return response()->json([
            'status' => 'failed',
            'message' => "{$model} not found"
        ], 404);
    }
}
