<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    /**
     * @property int $id
     * @property string $name
     * @property string $email
     * @property string $password
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function userProducts()
    {
        return $this->hasMany(UserProduct::class);
    }
    public function products()
    {
        return $this->hasManyThrough(Product::class, UserProduct::class, 'user_id', 'id', 'id', 'product_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function orderProducts()
    {
        return $this->hasManyThrough(OrderProduct::class, Order::class, 'user_id', 'id', 'id', 'order_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
