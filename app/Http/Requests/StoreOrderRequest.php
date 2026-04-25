<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isCustomer();
    }

    public function rules(): array
    {
        return [
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:transfer,card,e_wallet',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_address.required' => 'Alamat pengiriman harus diisi',
            'shipping_city.required' => 'Kota harus diisi',
            'shipping_state.required' => 'Provinsi harus diisi',
            'shipping_postal_code.required' => 'Kode pos harus diisi',
            'payment_method.required' => 'Metode pembayaran harus dipilih',
        ];
    }
}
