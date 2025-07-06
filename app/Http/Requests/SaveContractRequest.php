<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveContractRequest extends FormRequest
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
            'designation_id' => ['required'],
            'employee' => ['required'],
            'start_date' => ['required','date_format:d/m/Y'],
            'end_date' => ['required','after:start_date','date_format:d/m/Y'],
            'rate_type' => ['required'],
            'rate' => ['required','numeric']
        ];
    }

    public function attributes()
    {
        return [
            'designation_id' => 'designation',
            'start_date' => 'start date',
            'end_date' => 'end date',
            'rate_type' => 'rate type'
        ];
    }
}
