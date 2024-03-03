<?php

namespace App\Traits;


trait apiResponser{
    protected function Response($status, $message, $data, $code) 
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }
}