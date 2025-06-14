<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use Notifiable;
    use CrudTrait;
    use HasFactory;

  protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'address',
    'city',
    'postal_code',
    'province'
  ];

    protected $hidden = ['password'];


    public function orders()
  {
    return $this->hasMany(Order::class, 'email', 'email');
  }



    /**
     * Scope to search customers by email, name, or phone
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('email', 'LIKE', "%{$term}%")
                ->orWhere('name', 'LIKE', "%{$term}%")
                ->orWhere('phone', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        return trim("{$this->address}, {$this->city}, {$this->province} {$this->postal_code}");
    }

}
