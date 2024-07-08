<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionUpdateRequest extends FormRequest
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
            'name'          => [
                'required',
                'unique:sections,name,' . $this->section->id
            ],
            'shorthand'     => [
                'required'
            ],
            'division'   => [
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
            "name.required"         => "Section is required.",
            "name.unique"           => "Section must be unique.",
            "shorthand.required"    => "Section shorthand is required.",
            "division_id.required"  => "Division is required."
        ];
    }
}
