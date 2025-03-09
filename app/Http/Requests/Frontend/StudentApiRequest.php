<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class StudentApiRequest extends FormRequest
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
            'student_name' => 'required|string|max:255',
            'student_dob' => 'required|date',
            'nationality' => 'required|string|max:255',
            'student_ph' => 'required|string|max:20',
            'student_gender' => 'required|in:Male,Female,Other',
            'student_mail' => 'nullable|email|max:255|unique:students,student_mail',
            'marital_status' => 'nullable|string|max:255',
            'student_address' => 'required|string|max:255',
            'student_past_education' => 'nullable|string|max:255',
            'student_past_qualification' => 'nullable|string|max:255',
            'current_job_position' => 'nullable|string|max:255',
            'graduated' => 'boolean',
            'nrcnos' => 'nullable|array',
            'nrcnos.nrcs_id' => 'nullable|integer',
            // 'nrcnos.nrc_code' => 'nullable|string|max:255',
            'nrcnos.type' => 'nullable|string|max:1',
            'nrcnos.nrc_num' => 'nullable|string|max:255',
            'nrcnos.name_en' => 'nullable|string|max:255',
            'student_avatar' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            // 'student_avatar.*' => 'nullable|string|max:255',
            'approval_document' => 'nullable|array',
            'approval_document.*' => 'file|mimes:pdf,jpg,png,jpeg',
            'family_members' => 'array',
            'family_members.*.name' => 'required|string',
            'family_members.*.relationship' => 'required|string',
            'family_members.*.ph_no' => 'required|string',
            'family_members.*.address' => 'required|string',
            'family_members.*.profession' => 'nullable|string',
            'family_members.*.income' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'student_name.required' => 'Student name is required',
            'student_dob.required' => 'Date of birth is required',
            'student_dob.date' => 'Date of birth must be a valid date',
            'nationality.required' => 'Nationality is required',
            'student_ph.required' => 'Phone number is required',
            'student_gender.required' => 'Gender is required',
            'student_mail.email' => 'Email must be a valid email address',
            'student_mail.unique' => 'Email has already been taken',
            'student_nrc.unique' => 'NRC has already been taken',
            'graduated.required' => 'Graduation status is required',
            'familyMembers.*.name.required' => 'Family member name is required',
            'familyMembers.*.relationship.required' => 'Family member relationship is required',
            'familyMembers.*.ph_no.required' => 'Family member phone number is required',
            'familyMembers.*.address.required' => 'Family member address is required',
            'familyMembers.*.profession.required' => 'Family member profession is required',
            'familyMembers.*.income.required' => 'Family member income is required',

        ];
    }
}