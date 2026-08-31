<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyLocation extends Model
{
    protected $table = 'supply_location';

    protected $fillable = [
        'id',
        'name',
        'address',
        'city',
        'province',
        'country',
        'postal',
        'phone',
        'ice_cost',
        'courier',
        'active',
    ];
}
