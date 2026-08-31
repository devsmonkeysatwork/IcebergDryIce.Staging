<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Product extends Model
{
    use HasFactory;
    use CrudTrait;

    protected $fillable = [
        'product_name',
        'price',
        'unit',
        'available_to_account_holders',
    ];


    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}



