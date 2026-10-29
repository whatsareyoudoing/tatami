<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    // Manusia butuh lebih dari 3 detik untuk mengisi form; bot biasanya langsung submit
    public const MIN_FILL_SECONDS = 3;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => Str::lower(trim((string) $this->email)),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:100'],
            // Email yang belum diverifikasi tidak dianggap terpakai (akun lamanya akan diganti)
            'email'    => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->whereNotNull('email_verified_at')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Anti-bot: field tersembunyi yang harus kosong + waktu form dibuka
            'website'     => ['prohibited'],
            'form_token'  => ['required', 'string'],
        ];
    }

    /**
     * Tolak form yang dikirim terlalu cepat (atau token waktunya dipalsukan).
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                try {
                    $startedAt = (int) Crypt::decryptString((string) $this->input('form_token'));
                } catch (DecryptException) {
                    $startedAt = null;
                }

                if (!$startedAt || now()->timestamp - $startedAt < self::MIN_FILL_SECONDS) {
                    $validator->errors()->add('form', 'Pendaftaran gagal diproses, silakan coba lagi.');
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'        => 'Email ini sudah terdaftar. Silakan login.',
            'password.confirmed'  => 'Konfirmasi password tidak sama.',
            'password.min'        => 'Password minimal 8 karakter.',
            'website.prohibited'  => 'Pendaftaran gagal diproses, silakan coba lagi.',
            'form_token.required' => 'Pendaftaran gagal diproses, silakan coba lagi.',
        ];
    }

    public function attributes(): array
    {
        return ['name' => 'nama'];
    }
}
