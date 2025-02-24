<?php

namespace App\Http\Controllers;

use App\Http\Requests\Mail\MailRequest;
use App\Http\Responses\SuccessResponse;
use App\Services\MailService;

class MailController extends Controller {
    public function __construct(private readonly MailService $service) {}

    public function send(MailRequest $request): SuccessResponse {
        $this->service->sendMail($request->validated());

        return new SuccessResponse(['message' => 'Mail sent']);
    }
}
