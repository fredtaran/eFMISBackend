<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AllocationRequest extends FormRequest
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
            'program'  => [
                'required',
                'unique:allocations,program'
            ],
            'code'  => [
                'required',
                'unique:allocations,code'
            ],
            'amount'  => [
                'required',
            ],
            'line'  => [
                'required',
            ],
            'fundSource'  => [
                'required',
            ],
            'section'  => [
                'required',
            ],
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
            'program.required'      => 'Program title is required.',
            'program.unique'        => 'Program title must be unique.',
            'code.required'         => 'Program code is required.',
            'code.unique'           => 'Program code must be unique.',
            'amount.required'       => 'Allocation amount is required.',
            'line.required'         => 'Line item is required.',
            'fundSource.required'   => 'Fund source is required.',
            'section.required'      => 'Section is required.'
        ];
    }
}
