<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Wishlist;
use App\Models\Product;

class User extends Authenticatable implements MustVerifyEmail{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }
    public function orders()
{
    return $this->hasMany(Order::class);
}
public function wishlists()
{
    return $this->hasMany(Wishlist::class);
}

public function wishlistProducts()
{
    return $this->belongsToMany(
        Product::class,
        'wishlists'
    )->withTimestamps();
}
public function productViews()
{
    return $this->hasMany(ProductView::class);
}
}