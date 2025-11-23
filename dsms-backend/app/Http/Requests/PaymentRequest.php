<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'enrollment_id' => 'required|integer|exists:enrollments,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'transaction_reference' => 'nullable|string|max:100|unique:payments,transaction_reference',
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment_id.required' => 'Enrollment ID is required.',
            'transaction_reference.unique' => 'The transaction reference already exists.',
            'amount_paid.required' => 'Amount paid is required.',
            'payment_method_id.required' => 'Payment method is required.',
        ];
    }
}
