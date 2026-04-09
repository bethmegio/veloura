<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'active_bookings' => $user->bookings()
                ->whereIn('status', ['confirmed', 'rented'])
                ->count(),
            'completed_bookings' => $user->bookings()
                ->where('status', 'completed')
                ->count(),
            'pending_bookings' => $user->bookings()
                ->where('status', 'pending')
                ->count(),
        ];
        
        $recentBookings = $user->bookings()
            ->with(['gown', 'gown.shop'])
            ->latest()
            ->take(5)
            ->get();
            
        $pendingReviews = $user->bookings()
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->with('gown')
            ->get();
            
        return view('customer.dashboard', compact('stats', 'recentBookings', 'pendingReviews'));
    }
}