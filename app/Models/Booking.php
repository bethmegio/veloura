<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'customer_id',
        'gown_id',
        'start_date',
        'end_date',
        'total_days',
        'subtotal',
        'deposit_amount',
        'total_amount',
        'status',
        'special_requests',
        'pickup_date',
        'return_date',
        'cancellation_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'pickup_date' => 'date',
        'return_date' => 'date',
        'subtotal' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Boot method to auto-generate booking number
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($booking) {
            $booking->booking_number = 'VL-' . strtoupper(Str::random(8));
            $booking->total_days = $booking->start_date->diffInDays($booking->end_date) + 1;
            $booking->subtotal = $booking->gown->price_per_day * $booking->total_days;
            $booking->deposit_amount = $booking->gown->deposit ?? 0;
            $booking->total_amount = $booking->subtotal + $booking->deposit_amount;
        });
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function gown()
    {
        return $this->belongsTo(Gown::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // Helper methods
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function canBeReviewed()
    {
        return $this->status === 'completed' && !$this->review;
    }
}