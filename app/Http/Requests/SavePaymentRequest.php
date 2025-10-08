<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee' => ['required','regex:/^\d+-[a-zA-Z ]+$/'],
            'payroll_id' => ['required'],
            'amount' => ['required','numeric'],
            'payment_date' => ['required','date_format:d/m/Y'],
            'payment_method' => ['required'],
            'reference' => ['nullable']
        ];
    }

    public function attributes()
    {
        return [
            'payroll_id' => 'payroll period',
            'payment_date' => 'payment date',
            'payment_method' => 'payment method'
        ];
    }
}
