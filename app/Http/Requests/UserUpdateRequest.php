<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'firstname'     => ['required'],
            'middlename'    => [''],
            'lastname'      => ['required'],
            'suffix'        => [''],
            'username'      => [
                'required', 
                Rule::unique('users')->ignore($this->user->id)
            ],
            'division'      => ['required'],
            'roles'         => ['required']
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
            'division.required'     => 'Division is required.',
            'roles.required'        => 'User roles is required'
        ];
    }
}
