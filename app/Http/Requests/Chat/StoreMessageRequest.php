<?php
namespace App\Http\Requests\Chat;
use Illuminate\Foundation\Http\FormRequest;
class StoreMessageRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        return ['content' => 'required|string|max:1000'];
    }
}
