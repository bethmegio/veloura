<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isShopOwner()
    {
        return $this->role === 'shop_owner';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    // Relationships
    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    // Update this relationship to match the foreign key 'customer_id'
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}