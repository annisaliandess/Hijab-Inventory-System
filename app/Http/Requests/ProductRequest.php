<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID produk saat update untuk mengecualikan SKU uniknya sendiri
        $productId = $this->route('product') ? $this->route('product')->id : null;

        return [
            'category_id'   => ['required', 'exists:categories,id'],
            'sku'           => [
                'required', 
                'string', 
                'max:50', 
                Rule::unique('products', 'sku')->ignore($productId)
            ],
            'name'          => ['required', 'string', 'max:255'],
            'material'      => ['nullable', 'string', 'max:100'],
            'color'         => ['nullable', 'string', 'max:100'],
            'cost_price'    => ['required', 'numeric', 'min:0'],
            'price'         => ['required', 'numeric', 'min:0'],
            'stock'         => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:1'],
            'image'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'description'   => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required'   => 'Kategori hijab wajib dipilih.',
            'category_id.exists'     => 'Kategori yang dipilih tidak valid di database.',
            'sku.required'           => 'Kode SKU unik wajib diisi.',
            'sku.unique'             => 'Kode SKU ini sudah dipakai oleh produk hijab lain.',
            'name.required'          => 'Nama produk hijab wajib diisi.',
            'cost_price.required'    => 'Harga modal / beli wajib diisi.',
            'cost_price.min'         => 'Harga modal tidak boleh bernilai negatif.',
            'price.required'         => 'Harga jual konsumen wajib diisi.',
            'price.min'              => 'Harga jual tidak boleh bernilai negatif.',
            'stock.required'         => 'Jumlah stok wajib diisi.',
            'stock.min'              => 'Jumlah stok tidak boleh bernilai negatif.',
            'minimum_stock.required' => 'Batas stok minimum wajib diisi.',
            'minimum_stock.min'      => 'Batas stok minimum minimal bernilai 1 pcs.',
            'image.image'            => 'File yang diunggah harus berupa gambar.',
            'image.mimes'            => 'Format foto harus berupa JPG, PNG, atau WEBP.',
            'image.max'              => 'Ukuran foto maksimal adalah 2MB.',
        ];
    }
}