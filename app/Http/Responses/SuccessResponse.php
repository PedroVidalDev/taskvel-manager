<?php

namespace App\Http\Responses;

use App\Enums\StatusApi;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SuccessResponse extends ApiResponse {
    public function __construct($data = null) {
        parent::__construct(StatusApi::SUCCESS->value, $data, Response::HTTP_OK);
    }
}
