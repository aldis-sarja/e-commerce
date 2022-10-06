<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'order_code',
        'purchases'
    ];

    public function getSubTotal(): int
    {
        $sum = 0;
        foreach ($this->purchases as $purchase) {
            $sum += $purchase->product->price;
        }
        return $sum;
    }

    public function getTotal(): int
    {
        $sum = 0;
        foreach ($this->purchases as $purchase) {
            $sum += $purchase->product->price
                + $purchase->product->price
                * $purchase->product->VAT / 100;
        }
        return round($sum);
    }

    public function getVatSum(): int
    {
        $sum = 0;
        foreach ($this->purchases as $purchase) {
            $sum += $purchase->product->price * $purchase->product->VAT / 100;
        }
        return round($sum);
    }
}
