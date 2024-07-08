<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FundSourceRequest extends FormRequest
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
            'name'  => [
                'required',
                'unique:fund_sources,name'
            ],
            'code'  => [
                'required',
            ],
            'line'  => [
                'required',
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
            'name.required' => 'Fund source is required.',
            'name.unique'   => 'Fund source name must be unique.',
            'code.unique'   => 'Fund source code is required.',
            'line.unique'   => 'Line item is required.'
        ];
    }
}
