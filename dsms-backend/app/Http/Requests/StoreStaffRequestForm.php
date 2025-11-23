<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequestForm extends FormRequest
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
            'gender'        => 'required|string|in:male,female',
            'email'         => 'required|email|unique:users,email',
            'phone_number'  => 'required|string|max:20|unique:users,phone_number',
            'password'      => 'required|string|min:8|confirmed',
            // Staff fields
            'role_id'        => 'required|integer|exists:roles,id',
            'branch_id'      => 'required|integer|exists:branches,id',
            'license_number'    => 'nullable|string|max:50|unique:staff,license_number',
            'transmission_type' => 'nullable|integer|exists:transmission_types,id',
        ];
    }
}
