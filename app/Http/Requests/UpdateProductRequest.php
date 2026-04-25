<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($this->product),
            ],
            'category_id' => 'required|exists:categories,id',
            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($this->product),
            ],
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk harus diisi',
            'name.unique' => 'Nama produk sudah terdaftar',
            'sku.required' => 'SKU harus diisi',
            'sku.unique' => 'SKU sudah terdaftar',
            'category_id.required' => 'Kategori harus dipilih',
            'price.required' => 'Harga harus diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'discount_price.lt' => 'Harga diskon harus lebih kecil dari harga normal',
            'stock.required' => 'Stok harus diisi',
        ];
    }
}
