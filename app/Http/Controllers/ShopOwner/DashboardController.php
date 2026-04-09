<?php

namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Gown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        
        if (!$shop) {
            return redirect()->route('shop.setup')
                ->with('warning', 'Please set up your shop first!');
        }
        
        $gownIds = $shop->gowns->pluck('id');
        
        $stats = [
            'total_gowns' => $shop->gowns()->count(),
            'available_gowns' => $shop->gowns()->where('is_available', true)->count(),
            'total_bookings' => Booking::whereIn('gown_id', $gownIds)->count(),
            'pending_bookings' => Booking::whereIn('gown_id', $gownIds)
                ->where('status', 'pending')
                ->count(),
            'confirmed_bookings' => Booking::whereIn('gown_id', $gownIds)
                ->where('status', 'confirmed')
                ->count(),
            'rented_bookings' => Booking::whereIn('gown_id', $gownIds)
                ->where('status', 'rented')
                ->count(),
            'completed_bookings' => Booking::whereIn('gown_id', $gownIds)
                ->where('status', 'completed')
                ->count(),
            'total_revenue' => Booking::whereIn('gown_id', $gownIds)
                ->where('status', 'completed')
                ->sum('total_amount'),
        ];
        
        $recentBookings = Booking::with(['customer', 'gown'])
            ->whereIn('gown_id', $gownIds)
            ->latest()
            ->take(10)
            ->get();
            
        $popularGowns = Gown::withCount('bookings')
            ->where('shop_id', $shop->id)
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();
            
        return view('shop-owner.dashboard', compact('stats', 'recentBookings', 'popularGowns', 'shop'));
    }
}