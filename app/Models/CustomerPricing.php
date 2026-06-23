<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPricing extends Model
{
    protected $table = 'customer_pricing';

    protected $fillable = [
        'customer_id',
        'ice_price',
        'box_price',
        'hazmat_fee',
        'delivery_fee',
        'pickup_fee',
        'other_charges',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
