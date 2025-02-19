<?php

namespace App\Jobs;

use App\Mail\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class MailJob implements ShouldQueue
{
    use Queueable;

    private array $incoming;

    public function __construct(array $incoming)
    {
        $this->incoming = $incoming;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->incoming["email"])->send(
            new Notification(
                $this->incoming["title"],
                $this->incoming["text"]
            )
        );
    }
}
