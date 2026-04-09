<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'shop_name',
        'slug',
        'description',
        'logo',
        'address',
        'phone',
        'email',
        'status',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function gowns()
    {
        return $this->hasMany(Gown::class);
    }

    public function getActiveGownsCountAttribute()
    {
        return $this->gowns()->where('is_available', true)->count();
    }

    public function getTotalBookingsAttribute()
    {
        $gownIds = $this->gowns->pluck('id');
        return Booking::whereIn('gown_id', $gownIds)->count();
    }
}