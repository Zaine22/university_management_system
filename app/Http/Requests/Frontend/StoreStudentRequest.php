<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_name' => ['required', 'string', 'max:255'],
            'register_no' => ['required'],
            'student_slug' => ['required'],
            'student_dob' => ['required', 'string'],
            'student_avatar' => [''],
            'student_nrc' => [''],
            'nationality' => ['required'],
            'student_ph' => ['required'],
            'student_gender' => ['required'],
            'student_mail' => ['required'],
            'marital_status' => ['required'],
            'student_admission_date' => [''],
            'student_address' => ['required'],
            'student_past_education' => [''],
            'student_past_qualification' => [''],
            'current_job_position' => ['required'],
            'graduated' => ['required'],
            'approval_document' => [''],
        ];
    }
}