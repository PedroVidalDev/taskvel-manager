<?php

namespace App\Http\Responses;

use App\Enums\StatusApi;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NoContentResponse extends ApiResponse {
    public function __construct() {
        parent::__construct(StatusApi::SUCCESS->value, null, Response::HTTP_NO_CONTENT);
    }
}
