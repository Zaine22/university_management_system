<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
 */

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::call(function () {
//     $overdueTransactions = Transaction::where('due_date', '<=', Carbon::now())->where('status', 'new')->get();
//     foreach ($overdueTransactions as $transaction) {
//         TransactionOverdueJob::dispatch($transaction);
//     }
// })->daily();
