<?php

namespace App\Models\Order;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        static::creating(function(Order $order) {
            $order->uuid = Uuid::uuid4();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'payment_method',
    ];

    // Relatioships
    /** @return BelongsTo  */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** @return HasMany  */
    public function itens()
    {
        return $this->hasMany(OrderItens::class, 'order_id');
    }
}
