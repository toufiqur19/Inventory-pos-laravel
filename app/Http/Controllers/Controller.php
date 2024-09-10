<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{

    public function sendSuccess($message, $data, $statusCode = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            $data,
        ];
        return response()->json($response, $statusCode);
    }
    public function sendError($message, $statusCode = 400, $errors = [])
    {
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ];

        return response()->json($response, $statusCode);
    }
 
}