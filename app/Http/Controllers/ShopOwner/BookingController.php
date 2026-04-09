<?php

namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $shop = Auth::user()->shop;
        $gownIds = $shop->gowns->pluck('id');
        
        $status = $request->get('status', 'all');
        
        $query = Booking::with(['customer', 'gown'])
            ->whereIn('gown_id', $gownIds);
            
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $bookings = $query->latest()->paginate(15);
        
        return view('shop-owner.bookings.index', compact('bookings', 'status'));
    }
    
    public function show(Booking $booking)
    {
        $this->authorizeBooking($booking);
        
        $booking->load(['customer', 'gown']);
        
        return view('shop-owner.bookings.show', compact('booking'));
    }
    
    public function confirm(Booking $booking)
    {
        $this->authorizeBooking($booking);
        
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking cannot be confirmed at this stage.');
        }
        
        $booking->update(['status' => 'confirmed']);
        
        return back()->with('success', 'Booking confirmed! Notify customer for pickup.');
    }
    
    public function markPickup(Booking $booking)
    {
        $this->authorizeBooking($booking);
        
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Booking must be confirmed before pickup.');
        }
        
        $booking->update([
            'status' => 'rented',
            'pickup_date' => now(),
        ]);
        
        return back()->with('success', 'Gown marked as picked up.');
    }
    
    public function markReturn(Booking $booking)
    {
        $this->authorizeBooking($booking);
        
        if ($booking->status !== 'rented') {
            return back()->with('error', 'Gown must be marked as rented before return.');
        }
        
        $booking->update([
            'status' => 'returned',
            'return_date' => now(),
        ]);
        
        return back()->with('success', 'Gown marked as returned. Complete the rental process.');
    }
    
    public function complete(Booking $booking)
    {
        $this->authorizeBooking($booking);
        
        if ($booking->status !== 'returned') {
            return back()->with('error', 'Gown must be returned before completion.');
        }
        
        $booking->update(['status' => 'completed']);
        
        return back()->with('success', 'Rental completed successfully!');
    }
    
    public function cancel(Booking $booking)
    {
        $this->authorizeBooking($booking);
        
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Booking cannot be cancelled at this stage.');
        }
        
        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => request('reason', 'Cancelled by shop'),
        ]);
        
        return back()->with('success', 'Booking cancelled.');
    }
    
    private function authorizeBooking($booking)
    {
        $shopId = Auth::user()->shop->id;
        if ($booking->gown->shop_id !== $shopId) {
            abort(403, 'Unauthorized action.');
        }
    }
}