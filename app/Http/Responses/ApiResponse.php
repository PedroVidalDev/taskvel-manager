<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse extends JsonResponse {
    public function __construct($message = null, $data = null, $status = 200) {
        $response = [
            'message' => $message,
            'data' => $data,
            'status' => $status
        ];

        parent::__construct($response, $status);
    }
}
