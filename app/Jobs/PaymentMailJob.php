<?php

namespace App\Jobs;

use App\Mail\PaymentMail;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PaymentMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Student $student, protected Payment $payment) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->student->student_mail)->send(new PaymentMail($this->student, $this->payment));
    }
}
