<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'ice_order_id',
        'change_type',
        'quantity',
        'description',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
