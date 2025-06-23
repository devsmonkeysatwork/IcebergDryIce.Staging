<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'email',
        'phone',
        'amount_of_ice',
        'amount_of_boxes',
        'origin',
        'recurring',
        'location_name',
        'address',
        'unit',
        'city',
        'postal_code',
        'province',
        'country',
        'pickup_delivery',
        'status',
        'hazmat',
        'delivery_date',
        'notes',
        'sub_total',
        'delivery_cost',
        'tax',
        'total_cost',
        'payment_status',
    ];

    public function scopeToday($query)
    {
        return $query->whereDate('delivery_date', Carbon::today());
    }

    public function scopeFuture($query)
    {
        return $query->whereDate('delivery_date', '>', Carbon::today());
    }

    public function scopePast($query)
    {
        return $query->whereDate('delivery_date', '<', Carbon::today());
    }

    public function scopeAll($query)
    {
        return $query;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'email', 'email');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}
