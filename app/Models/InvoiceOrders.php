<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceOrders extends Model
{

    protected $fillable = [
        'order_type',
        'invoiceable_type',
        'invoiceable_id',
        'invoice_id',
    ];
}
