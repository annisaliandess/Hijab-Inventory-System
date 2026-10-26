<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Izinkan pengguna yang sudah lolos middleware admin
    }

    public function rules(): array
    {
        // Ambil ID kategori saat update agar tidak bentrok dengan namanya sendiri
        $categoryId = $this->route('category') ? $this->route('category')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori hijab wajib diisi.',
            'name.unique'   => 'Nama kategori ini sudah terdaftar sebelumnya.',
        ];
    }
}