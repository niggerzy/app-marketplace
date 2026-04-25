<?php
namespace App\Http\Requests\Order;
use Illuminate\Foundation\Http\FormRequest;
class StoreOrderRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        return [
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'customer_address' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }
}
