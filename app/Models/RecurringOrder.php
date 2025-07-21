<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringOrder extends Model
{
    protected $fillable = [
        'order_id',
        'scheduled_delivery_date',
        'status',
    ];

    protected $casts = [
        'scheduled_delivery_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
