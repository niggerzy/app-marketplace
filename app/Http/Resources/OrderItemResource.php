<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class OrderItemResource extends JsonResource {
    public function toArray($request) {
        return [
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'subtotal' => $this->subtotal,
        ];
    }
}
