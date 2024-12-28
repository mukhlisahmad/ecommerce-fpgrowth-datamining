<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'customer_id',
        'id_product',
        'category_id',
        'total_price',
        'status',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($order) {
            if ($order->isDirty('status') && $order->status === 'completed') {
                $existingCategories = DB::table('order_product')
                    ->where('customer_id', $order->customer_id)
                    ->pluck('category_id')
                    ->toArray();
                $newCategory = (string) $order->category_id;
                $categoryArray = array_merge($existingCategories, [$newCategory]);
                $categoryIds = implode(',', $categoryArray);
                DB::table('order_product')->updateOrInsert(
                    ['customer_id' => $order->customer_id],
                    [
                        'category_id' => $categoryIds,
                        'updated_at' => now(),
                    ]
                );
            }
        });
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
