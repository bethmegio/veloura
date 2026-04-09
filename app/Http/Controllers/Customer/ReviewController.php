<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(Booking $booking)
    {
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }
        
        if (!$booking->canBeReviewed()) {
            return redirect()->route('customer.bookings.show', $booking)
                ->with('error', 'You cannot review this booking yet.');
        }
        
        return view('customer.reviews.create', compact('booking'));
    }
    
    public function store(Request $request, Booking $booking)
    {
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);
        
        $data = $request->except('images');
        $data['user_id'] = Auth::id();
        $data['gown_id'] = $booking->gown_id;
        $data['booking_id'] = $booking->id;
        $data['is_approved'] = false; // Needs admin approval
        
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('reviews', 'public');
            }
            $data['images'] = json_encode($images);
        }
        
        Review::create($data);

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'Thank you for your review! It will be visible after admin approval.');
    }
    
    public function myReviews()
    {
        $reviews = Auth::user()->reviews()
            ->with('gown')
            ->latest()
            ->paginate(10);
            
        return view('customer.reviews.index', compact('reviews'));
    }
}