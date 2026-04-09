<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Gown;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create(Gown $gown)
    {
        if (!$gown->is_available) {
            return redirect()->route('customer.gowns.show', $gown->slug)
                ->with('error', 'This gown is currently unavailable.');
        }
        
        return view('customer.bookings.create', compact('gown'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'gown_id' => 'required|exists:gowns,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'special_requests' => 'nullable|string|max:500',
        ]);
        
        $gown = Gown::findOrFail($request->gown_id);
        
        if (!$gown->isAvailableForDates($request->start_date, $request->end_date)) {
            return back()->with('error', 'This gown is not available for the selected dates.')
                ->withInput();
        }
        
        $booking = Booking::create([
            'customer_id' => Auth::id(),
            'gown_id' => $gown->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'special_requests' => $request->special_requests,
            'status' => 'pending'
        ]);
        
        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', 'Booking created successfully! Please wait for shop confirmation.');
    }
    
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with(['gown', 'gown.shop'])
            ->latest()
            ->paginate(10);
            
        return view('customer.bookings.index', compact('bookings'));
    }
    
    public function show(Booking $booking)
    {
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }
        
        $booking->load(['gown', 'gown.shop']);
        
        return view('customer.bookings.show', compact('booking'));
    }
    
    public function cancel(Booking $booking)
    {
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }
        
        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'This booking cannot be cancelled at this stage.');
        }
        
        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => request('reason', 'Cancelled by customer'),
        ]);
        
        return redirect()->route('customer.bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }
    
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'gown_id' => 'required|exists:gowns,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        
        $gown = Gown::find($request->gown_id);
        $isAvailable = $gown->isAvailableForDates($request->start_date, $request->end_date);
        
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $subtotal = $gown->price_per_day * $totalDays;
        $total = $subtotal + ($gown->deposit ?? 0);
        
        return response()->json([
            'available' => $isAvailable,
            'total_days' => $totalDays,
            'subtotal' => number_format($subtotal, 2),
            'deposit' => number_format($gown->deposit ?? 0, 2),
            'total' => number_format($total, 2),
        ]);
    }
}