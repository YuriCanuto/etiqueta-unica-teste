<?php

namespace App\Http\Resources\Session;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'uuid'          => $this->uuid,
            'name'          => $this->name,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'active'        => $this->active,
            'last_login_at' => $this->last_login_at ? $this->last_login_at->format('Y-m-d H:i:s') : null,
            'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'    => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
