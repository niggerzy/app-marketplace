<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class MessageResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'sender' => $this->whenLoaded('sender', fn() => ['id' => $this->sender->id, 'name' => $this->sender->name]),
            'created_at' => $this->created_at,
        ];
    }
}
