<?php

namespace App\Services;

use App\Mail\Notification;
use Illuminate\Support\Facades\Mail;

class MailService {
    public function sendMail(mixed $data): array {
        Mail::to($data->email)->send(new Notification($data->title, $data->text));

        return [
            'message' => 'Email sent successfully',
            'email' => $data->email,
        ];
    }
}
