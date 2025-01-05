<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $rememberTokenName = 'remember_token';

    protected $fillable = [
        'name',
        'email',
        // 'phone',
        // 'address',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }


    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
