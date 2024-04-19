<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function response($code, $message, $response)
    {
        return response()->json([
            'metaData' => [
                'code' => $code,
                'message' => $message
            ],
            'response' => $response
        ]);
    }
}
