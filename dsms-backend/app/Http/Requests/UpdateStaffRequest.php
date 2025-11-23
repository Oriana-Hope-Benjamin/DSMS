<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
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
             'firstname'     => 'sometimes|string|max:100',
            'lastname'      => 'sometimes|string|max:100',
            'gender'        => 'sometimes|string|in:male,female',
            'email'         => 'sometimes|email|unique:users,email',
            'phone_number'  => 'sometimes|string|max:20|unique:users,phone_number',
            'password'      => 'sometimes|string|min:8|confirmed',
            // Staff fields
            'role_id'        => 'sometimes|integer|exists:roles,id',
            'branch_id'      => 'sometimes|integer|exists:branches,id',
            'license_number'    => 'sometimes|string|max:50|unique:staff,license_number',
            'transmission_type' => 'sometimes|integer|exists:transmission_types,id',
        ];
    }
}
