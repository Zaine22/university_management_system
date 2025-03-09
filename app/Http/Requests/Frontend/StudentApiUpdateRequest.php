<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class StudentApiUpdateRequest extends FormRequest
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
            'student_name' => 'nullable|string|max:255',
            'student_avatar' => 'nullable|image|max:2048',
            'student_ph' => 'nullable|string|max:20',
            'student_mail' => 'nullable|email|max:255',
            'student_address' => 'nullable|string|max:255',
        ];
    }
}