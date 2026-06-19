<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceFlatCharges extends Model
{
    protected $fillable = [
        'invoice_id',
        'charge_key',
        'label',
        'amount',
    ];
}
