<?php

namespace App\Listeners;

use App\Events\EnrollmentCreated;

class SendEnrollmentNotification
{
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
    public function handle(EnrollmentCreated $event): void {}
}
