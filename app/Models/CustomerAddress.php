<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = [
        'customer_id',
        'address_label',
        'location_name',
        'address',
        'unit',
        'city',
        'postal_code',
        'province',
        'country',
        'is_default',
        'is_active',
        'delivery_instructions'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // ✅ UPDATED: Reflects the relationship name change
    public function orders()
    {
        return $this->hasMany(Order::class, 'address_id');
    }

    public function getFullAddressAttribute()
    {
        $parts = [
            $this->address,
            $this->unit ? "Unit {$this->unit}" : null,
            $this->city,
            $this->province,
            $this->postal_code,
            $this->country
        ];

        return implode(', ', array_filter($parts));
    }

    public function getShortAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->province}";
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', 1);
    }
}
