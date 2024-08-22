<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'firstname'             => ['required'],
            'middlename'            => [''],
            'lastname'              => ['required'],
            'suffix'                => [''],
            'username'              => ['required', 'unique:users,username'],
            'division'              => ['required'],
            'section'               => ['required'],
            'roles'                 => ['required'],
            'twg_classification'    => []
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
            'firstname.required'    => 'Firstname is required.',
            'lastname.required'     => 'Lastname is required',
            'username.required'     => 'Username is required',
            'username.unique'       => 'Username must be unique',
            'division.required'     => 'Division is required',
            'section.required'      => 'Section is required',
            'roles.required'        => 'User roles is required'
        ];
    }
}
