<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'dni' => $this->dni,
            'email' => $this->email,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'commune' => $this->commune,
            'region' => $this->region
        ];
    }
}
