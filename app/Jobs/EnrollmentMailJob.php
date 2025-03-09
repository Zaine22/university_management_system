<?php

namespace App\Jobs;

use App\Mail\EnrollmentMail;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnrollmentMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Student $student, protected Enrollment $enrollment) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->student->student_mail)->send(new EnrollmentMail($this->enrollment));
    }
}
