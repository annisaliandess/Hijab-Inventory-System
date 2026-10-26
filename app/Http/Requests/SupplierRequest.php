<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:150'],
            'phone'   => ['required', 'string', 'max:25'],
            'email'   => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Nama pemasok / supplier wajib diisi.',
            'phone.required' => 'Nomor telepon / WhatsApp pemasok wajib diisi.',
            'email.email'    => 'Format alamat email tidak valid.',
        ];
    }
}