<?php

namespace App\Http\Resources\Frontend;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Frontend\BatchApiResource;
use App\Http\Resources\Frontend\ResultApiResource;
use App\Http\Resources\Frontend\InvoiceApiResource;
use App\Http\Resources\Frontend\TimetableApiResource;
use App\Http\Resources\Frontend\EnrollmentApiResource;

class StudentApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'student_slug' => $this->student_slug,
            'attributes' => [
                'student_name' => $this->student_name,
                'student_dob' => $this->student_dob,
                'student_avatar' => $this->student_avatar,
                'student_nrc' => $this->student_nrc,
                'student_gender' => $this->student_gender,
                'nationality' => $this->nationality,
                'register_no' => $this->register_no,
                'student_ph' => $this->student_ph,
                'student_mail' => $this->student_mail,
                'student_admission_date' => $this->student_admission_date,
                'student_address' => $this->student_address,
            ],
            'timetables' => TimetableApiResource::collection($this->timetables),
            'batches' => BatchApiResource::collection($this->batches),
            'enrollments' => EnrollmentApiResource::collection($this->enrollments),
            'invoices' => InvoiceApiResource::collection($this->invoices),
            'results' => ResultApiResource::collection($this->results),
        ];
    }
}