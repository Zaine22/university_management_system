<?php

namespace App\Listeners;

use App\Events\TransactionDueDate;

class SendTransactionDueNotification
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
    public function handle(TransactionDueDate $event): void
    {
        //
    }
}
