<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->user->name,
            'mobile_number' => $this->user->mobile_number? $this->user->mobile_number: null,
            'address' => $this->user->address? $this->user->address :null,
            'category_id' => $this->category_id,
            'image' => $this->image,
            'state' => $this->state,
            'demand_state' => $this->demand_state,
            'price' => $this->price,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
