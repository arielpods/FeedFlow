<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\NewAnswerNotificationMail;
use Illuminate\Support\Facades\Mail;

class SendNewAnswerNotification
{
    use InteractsWithQueue;

    protected string $recipientEmail = 'admin@example.com';
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        Mail::to('admin@example.com')->send(
            new SendNewAnswerNotification($event->answer)
        );
    }
}
