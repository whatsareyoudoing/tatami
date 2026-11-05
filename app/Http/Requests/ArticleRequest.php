<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Dipakai untuk store dan update artikel.
 */
class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Checkbox yang tidak dicentang tidak terkirim, jadi diisi manual
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
            'hapus_sampul'  => $this->boolean('hapus_sampul'),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'judul'        => ['required', 'string', 'max:255'],
            'ringkasan'    => ['nullable', 'string', 'max:300'],
            // Isi wajib berisi teks atau gambar, bukan hanya <p>&nbsp;</p> kosong dari editor
            'isi'          => ['required', 'string', 'max:500000', function ($attribute, $value, $fail) {
                if (trim(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5), " \t\n\r\0\x0B\u{A0}") === '' && !str_contains($value, '<img')) {
                    $fail('Isi artikel wajib diisi.');
                }
            }],
            'sampul'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_sampul' => ['boolean'],
            'is_published' => ['boolean'],
            'products'     => ['nullable', 'array', 'max:12'],
            'products.*'   => ['integer', 'distinct', 'exists:products,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'isi.required'  => 'Isi artikel wajib diisi.',
            'sampul.image'  => 'Sampul harus berupa gambar.',
            'sampul.max'    => 'Sampul maksimal 2 MB.',
            'products.max'  => 'Maksimal 12 produk per artikel.',
        ];
    }

    public function attributes(): array
    {
        return [
            'ringkasan' => 'ringkasan',
            'products'  => 'produk',
        ];
    }
}
