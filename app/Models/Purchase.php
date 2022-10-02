<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
