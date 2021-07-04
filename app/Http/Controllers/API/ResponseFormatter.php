<?php

namespace App\Http\Controllers\API;

class ResponseFormatter
{
    protected static $response = [
        'success' => 1,
        'message' => null,
        'data' => null
    ];

    public static function success($data = null, $message = null)
    {
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response);
    }

    public static function error($message = null)
    {
        self::$response['success'] = 0;
        self::$response['message'] = $message;

        return response()->json(self::$response);
    }
}
