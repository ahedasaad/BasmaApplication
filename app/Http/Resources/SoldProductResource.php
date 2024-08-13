<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SoldProductResource extends JsonResource
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
            'id'=>$this->id,
            'product_name' => $this->product->name,
            'sale_date' => $this->created_at->format('Y-m-d'),
            'representative_name' => $this->representative ? $this->representative->name : 'N/A',
            'price' => $this->product->price,
        ];
    }
}
