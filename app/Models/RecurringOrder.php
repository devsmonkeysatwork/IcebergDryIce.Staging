<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringOrder extends Model
{
    const OPEN = 'open';
    const COMPLETED = 'completed';
    const CANCELLED = 'cancelled';
    protected $fillable = [
        'order_id',
        'scheduled_delivery_date',
        'status',
        'recurring_payment_status',
    ];

    protected $casts = [
        'scheduled_delivery_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
