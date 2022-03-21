<?php

namespace App\Models\Product;

use App\Models\Order\OrderItens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        static::creating(function(Product $product) {
            $product->uuid = Uuid::uuid4();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'amount',
        'active'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean'
    ];

    // Relatioships
    /** @return BelongsTo  */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** @return BelongsTo  */
    public function orderItem()
    {
        return $this->belongsTo(OrderItens::class, 'product_id');
    }
}
