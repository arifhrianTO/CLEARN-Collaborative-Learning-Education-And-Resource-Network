<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_name' => ['required', 'string', 'max:255'],
            'category_description' => ['nullable', 'string'],
            'category_icon' => ['nullable', 'string', 'max:100'],
            'category_color' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_name.required' => 'Nama kategori wajib diisi.',
            'category_name.max' => 'Nama kategori maksimal 255 karakter.',
            'category_icon.max' => 'Nama icon terlalu panjang.',
            'category_color.max' => 'Kode warna terlalu panjang.',
        ];
    }
}