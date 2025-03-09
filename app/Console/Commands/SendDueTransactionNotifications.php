<?php

namespace App\Console\Commands;

use App\Jobs\TransactionOverdueJob;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendDueTransactionNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:due-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for transactions nearing their due date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysBeforeDue = 7;
        $transactions = Transaction::where('due_date', '=', Carbon::now()->addDays($daysBeforeDue)->toDateString())->get();

        foreach ($transactions as $transaction) {
            TransactionOverdueJob::dispatch($transaction);
        }

        return 0;

    }
}
