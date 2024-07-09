<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UacsBulkRequest extends FormRequest
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
            'uacs_file'     => [
                'required',
                'mimes:xlsx,xls',
                'max:10240'
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
            'uacs_file.required'    => 'File is required.',
            'uacs_file.mimes'       => 'Only .xlsx and .xls format is allowed.',
            'uacs_file.max'         => 'File must not exceed 10MB',
        ];
    }
}
