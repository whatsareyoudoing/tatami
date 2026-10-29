<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', Rule::unique('users')->ignore($this->user)],
            'username' => ['required', 'string', Rule::unique('users')->ignore($this->user)],
            'password' => ['nullable', 'min:8'],
            'role'  => ['required', 'exists:roles,id'],
        ];
    }

    // protected function failedValidation(Validator $validator)
    // {
    //     dd([
    //         'inputs' => $this->all(),
    //         'errors' => $validator->errors()->all(),
    //     ]);
    // }


}
