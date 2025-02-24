<?php

namespace App\Services;

use App\Jobs\MailJob;
use App\Mail\Notification;
use Illuminate\Support\Facades\Mail;

class MailService {
    public function sendMail(mixed $data): array {
        dispatch(new MailJob([
            'email' => $data->email,
            'title' => $data->title,
            'text' => $data->text
        ]));

        return [
            'message' => 'Email sent successfully',
            'email' => $data->email,
        ];
    }
}
