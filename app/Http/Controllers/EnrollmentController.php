<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\EnrollmentApiRequest;
use App\Models\Batch;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function enroll(EnrollmentApiRequest $request, Batch $batch)
    {

        $validatedData = $request->validated();

        $student_id = Student::where('user_id', Auth::user()->id)->firstOrFail()->id;

        $validatedData['student_id'] = $student_id;
        $validatedData['batch_id']   = $batch->id;

        $isEnrolled = Enrollment::where('student_id', $student_id)
            ->where(column: 'batch_id', operator: $batch->id)
            ->exists();

        if ($isEnrolled) {
            return response()->json(['message' => 'Student is already enrolled in this batch.']);
        } else {
            DB::transaction(function () use ($validatedData) {

                $enrollment = Enrollment::create([
                    'student_id'                 => $validatedData['student_id'],
                    'batch_id'                   => $validatedData['batch_id'],
                    'has_installment_plan'       => $validatedData['has_installment_plan'],
                    'enrollment_payment_amount'  => $validatedData['total_payment_amount'],
                    'total_payment_amount'       => $validatedData['total_payment_amount'],
                    'discount_percentage'        => null,
                    'discounted_payment_amount'  => null,
                    'additional_discount_amount' => null,
                ]);

                $enrollment->payment()->create();

                $enrollment->payment->transactions()->create([
                    'transaction_proof' => $validatedData['transaction_proof']->store('images/transactions'),
                    'payment_method' => $validatedData['payment_method'],
                ]);

                $enrollment->getEnrollment();

            });

        }

    }
}