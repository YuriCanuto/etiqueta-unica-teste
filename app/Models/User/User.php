<?php

namespace App\Models\User;

use App\Models\Company\Company;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Notifications\Welcome as WelcomeNotification;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected static function booted()
    {
        static::creating(function(User $user) {
            $user->uuid = Uuid::uuid4();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'last_login_ip',
        'last_login_at',
        'receive_messages',
        'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'receive_messages'  => 'boolean',
        'active'            => 'boolean'
    ];

    protected $attributes = [
        'receive_messages' => false
    ];

    // Relatioships
    /** @return HasMany  */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * Notifications
     */
    public function sendWelcomeNotification()
    {
        $this->notify(new WelcomeNotification($this));
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Mutators
     */
    public function setEmailAttribute($input)
    {
        if ($input)
            $this->attributes['email'] = mb_strtolower($input, 'UTF-8');
    }

    public function setPhoneAttribute($input)
    {
        if ($input)
            $this->attributes['phone'] = unmaskInput($input);
    }

    /**
     * Accesors
     */
    public function getLastLoginAttribute()
    {
        return ($this->last_login_at) ? $this->last_login_at->diffForHumans() : 'Não efetuou login';
    }
}
