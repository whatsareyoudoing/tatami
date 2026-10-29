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
            'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($this->category)],
            'slug' => ['required', Rule::unique('categories')->ignore($this->category)],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.required' => 'Nama kategori harus mengandung huruf atau angka.',
            'slug.unique'   => 'Nama kategori terlalu mirip dengan kategori yang sudah ada.',
        ];
    }
}
