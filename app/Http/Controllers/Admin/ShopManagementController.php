<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopManagementController extends Controller
{
    public function index()
    {
        $shops = Shop::with('owner')->latest()->paginate(10);
        return view('admin.shops.index', compact('shops'));
    }
    
    public function show(Shop $shop)
    {
        $shop->load(['owner', 'gowns']);
        $totalBookings = $shop->gowns->flatMap->bookings->count();
        $totalRevenue = $shop->gowns->flatMap->bookings
            ->where('status', 'completed')
            ->sum('total_amount');
            
        return view('admin.shops.show', compact('shop', 'totalBookings', 'totalRevenue'));
    }
    
    public function approve(Shop $shop)
    {
        $shop->update(['status' => 'active']);
        
        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop approved successfully!');
    }
    
    public function reject(Shop $shop)
    {
        $shop->update(['status' => 'inactive']);
        
        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop rejected successfully!');
    }
    
    public function toggleStatus(Shop $shop)
    {
        $newStatus = $shop->status === 'active' ? 'inactive' : 'active';
        $shop->update(['status' => $newStatus]);
        
        return back()->with('success', "Shop {$newStatus} successfully!");
    }
    
    public function destroy(Shop $shop)
    {
        $shop->delete();
        return redirect()->route('admin.shops.index')
            ->with('success', 'Shop deleted successfully!');
    }
}