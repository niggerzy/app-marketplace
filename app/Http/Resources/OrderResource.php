<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class OrderResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_address' => $this->customer_address,
            'total_price' => $this->total_price,
            'admin_notes' => $this->admin_notes,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
        ];
    }
}
