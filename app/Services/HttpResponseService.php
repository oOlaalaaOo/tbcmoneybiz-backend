<?php

namespace App\Services;

use Log;

class HttpResponseService
{
    public static function error($errorMessage, $statusCode = 500)
    {
        if (!$errorMessage) {
            throw new Exception('HttpResponseService: No errorMessage is given', 1);
        }

        Log::error('HttpResponseService:error - ' . $errorMessage);
        
        return response()->json([
            'success'   => (bool) false,
            'error' 	=> $errorMessage
        ], $statusCode);
    }

    public static function success($data, $statusCode = 200)
    {
        return response()->json([
            'success'   => (bool) true,
            'data'      => $data
        ], $statusCode);
    }
}
