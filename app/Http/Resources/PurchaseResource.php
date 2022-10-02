<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'order_code' => $this->order_code,
            'product_id' => $this->id,
            'name' => $this->product->name,
            'description' => $this->product->description,
            'price' => $this->product->price,
            'VAT' => $this->product->VAT,
        ];
    }
}
