<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    /** @use HasFactory<\Database\Factories\KeranjangFactory> */
    use HasFactory;
    protected $table = 'keranjangs';

    protected $fillable = [
        'customer_id',
        'id_product',
        'category_id',
        'total_price',
        'status',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('quantity');
    }

}
