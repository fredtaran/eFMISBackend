<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseDetailRequest extends FormRequest
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
            'reference_no'  => [
                'required',
                'unique:transactions,reference_no'
            ],
            'activity_title'    => [
                'required'
            ],
            'amount'            => [
                'required'
            ]
        ];
    }

    /**
     * Get the validation error messages that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function messages(): array
    {
        return [
            'reference_no.required'   => 'Reference number is required.',
            'reference_no.unique'     => 'Reference number must be unique.',
            'activity_title.required' => 'Activity title is required.',
            'amount.required'         => 'Purchase order amount is required.'
        ];
    }
}
