<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuyingResource extends JsonResource
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
            'product_id' => $this->product_id,
            'product_name' => $this->product->name,
            'representative_id' => $this->representative_id? $this->representative_id: null,
            'representative_name' => $this->representative? $this->representative->name: null,
            'product_image' => $this->product->image? asset('storage/' . $this->product->image): null,
            'state' => $this->state,
            'mobile_number' => $this->mobile_number,
            'address' => $this->address,
            'seller_mobile_number' => $this->product->user->mobile_number ? $this->product->user->mobile_number : null,
            'seller_address' => $this->product->user->address ? $this->product->user->address : null,
            'note' => $this->note? $this->note: null ,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
