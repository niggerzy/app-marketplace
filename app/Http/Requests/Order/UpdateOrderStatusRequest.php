<?php
namespace App\Http\Requests\Order;
use Illuminate\Foundation\Http\FormRequest;
class UpdateOrderStatusRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        return [
            'status' => 'required|in:pending,confirmed,processing,shipped,completed,cancelled',
            'admin_notes' => 'nullable|string',
        ];
    }
}
