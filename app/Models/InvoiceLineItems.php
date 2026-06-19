<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLineItems extends Model
{
    protected $fillable = [
        'invoice_id',
        'order_id',
        'product_id',
        'description',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
