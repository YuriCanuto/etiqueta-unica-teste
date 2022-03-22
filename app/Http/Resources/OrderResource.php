<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'             => $this->uuid,
            'payment_method' => $this->payment_method,
            'user'           => $this->user->name,
            'user_email'     => $this->user->email,
            'created_at'     => $this->created_at,
            'itens'          => OrderItensResource::collection($this->itens)
        ];
    }
}
