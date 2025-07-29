<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ManualPayment extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'manual_payments';

    protected $fillable = [
        'contact_name',
        'email',
        'order_number',
        'description',
        'amount',
        'recurring_order_id'
    ];

}
