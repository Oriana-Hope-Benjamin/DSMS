<?php

namespace App\Http\Requests;

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
            // User fields
            'firstname'     => 'required|string|max:100',
            'lastname'      => 'required|string|max:100',
            'gender'        => 'required|string|in:male,female,other',
            'email'         => 'required|email|unique:users,email',
            'phone_number'  => 'required|string|max:20',
            'password'      => 'required|string|min:8|confirmed',

            // Student fields
            'date_of_birth' => 'required|nullable|date',
            'nin'           => 'required|nullable|string|max:50',
            'learner_permit_number' => 'nullable|string|max:50',
            'enrollment_date'      => 'nullable|date',
            'address'      => 'required|string|max:255',

            // Foreign keys
            'branch_id'     => 'required|integer',
            'role_id'       => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.required' => 'First name is required.',
            'lastname.required'  => 'Last name is required.',
            'gender.required'    => 'Gender is required.',
            'branch_id.required' => 'Branch is required.',
            'role_id.required'   => 'Role is required.',
            'email.required'     => 'Email is required.',
            'email.email'        => 'Email must be a valid email address.',
            'email.unique'       => 'Email has already been used already.',
            'phone_number.required' => 'Phone number is required.',
            'password.required'  => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'date_of_birth.date' => 'Date of birth must be a valid date.',
        ];
    }
}
