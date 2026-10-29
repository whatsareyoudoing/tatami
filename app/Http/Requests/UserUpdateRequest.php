<?php

namespace App\Http\Requests;

use App\Models\Role;
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
            // Hanya role staf; staf tidak bisa diubah menjadi customer
            'role'  => ['required', Rule::exists('roles', 'id')->whereNot('name', Role::CUSTOMER)],
        ];
    }

    /**
     * Pengaman role, dicek setelah rules() lolos.
     * $this->user = user yang diedit (parameter route), $this->user() = user yang sedang login.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $target = $this->user;
                $roleChanged = (int) $this->input('role') !== (int) $target->roles()->value('roles.id');

                if (!$roleChanged) {
                    return;
                }

                if ($target->is($this->user())) {
                    $validator->errors()->add('role', 'Anda tidak bisa mengubah role akun sendiri.');
                } elseif ($target->isLastActiveAdmin()) {
                    $validator->errors()->add('role', 'Role admin terakhir tidak bisa diubah.');
                }
            },
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
