<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Slug dibuat dari nama sebelum validasi, supaya keunikannya ikut dicek.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug((string) $this->name),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     * Dipakai untuk store dan update; saat update, kategori yang sedang diedit diabaikan.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            // Keunikan nama dicek lewat slug: huruf besar/kecil dan tanda baca diabaikan ("Bantal" = "bantal!")
            'slug'  => ['required', Rule::unique('categories')->ignore($this->category)],
            // Wajib saat tambah; saat edit boleh kosong (gambar lama tetap dipakai)
            'image' => [$this->category ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'Gambar kategori wajib diisi.',
            'image.image'    => 'File harus berupa gambar.',
            'image.max'      => 'Gambar maksimal 2 MB.',
            'slug.required' => 'Nama kategori harus mengandung huruf atau angka.',
            'slug.unique'   => 'Kategori dengan nama ini sudah ada.',
        ];
    }
}
