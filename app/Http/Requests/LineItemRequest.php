<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LineItemRequest extends FormRequest
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
                'unique:line_items,name'
            ],
            'code'  => [
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
            'name.required' => 'Line item is required.',
            'name.unique'   => 'Line item must be unique.',
            'code.required' => 'Line item code is required.'
        ];
    }
}
