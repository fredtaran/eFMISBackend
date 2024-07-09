<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UacsRequest extends FormRequest
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
            'title'     => [
                'required',
                'unique:uacs,title'
            ],
            'code'      => ['required'],
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
            'title.required'    => 'UACS title is required.',
            'title.unique'      => 'UACS title must be unique.',
            'code.required'     => 'UACS code is required',
        ];
    }
}
