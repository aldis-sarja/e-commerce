<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'order_code' => $this->getOrderCode(),
            'purchases' => $this->getPurchases(),
            'price' => $this->getTotal(),
            'sub_total' => $this->getSubTotal(),
            'VAT_sum' => $this->getVatSum()
        ];
    }
}
