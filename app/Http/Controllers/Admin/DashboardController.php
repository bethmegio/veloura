<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Shop;
use App\Models\Gown;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_shop_owners' => User::where('role', 'shop_owner')->count(),
            'total_shops' => Shop::count(),
            'pending_shops' => Shop::where('status', 'pending')->count(),
            'active_shops' => Shop::where('status', 'active')->count(),
            'total_gowns' => Gown::count(),
            'available_gowns' => Gown::where('is_available', true)->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'total_revenue' => Booking::where('status', 'completed')->sum('total_amount'),
            'pending_reviews' => Review::where('is_approved', false)->count(),
        ];
        
        $recentBookings = Booking::with(['customer', 'gown'])
            ->latest()
            ->take(10)
            ->get();
            
        $recentShops = Shop::with('owner')
            ->latest()
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact('stats', 'recentBookings', 'recentShops'));
    }
}