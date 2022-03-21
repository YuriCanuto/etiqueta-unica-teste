<?php

namespace App\Models\Order;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class OrderItens extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        static::creating(function(OrderItens $orderItens) {
            $orderItens->uuid = Uuid::uuid4();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'amount',
        'coupon',
    ];

    // Relatioships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /** @return HasMany  */
    public function products()
    {
        return $this->hasMany(Product::class, 'product_id');
    }
}
