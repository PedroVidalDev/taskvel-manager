<?php

namespace App\Http\Responses;

use App\Enums\StatusApi;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CreatedResponse extends ApiResponse {
    public function __construct($data = null) {
        parent::__construct(StatusApi::CREATED, $data, Response::HTTP_CREATED);
    }
}
