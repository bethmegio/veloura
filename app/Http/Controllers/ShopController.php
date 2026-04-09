<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Gown;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::where('status', 'active')
            ->withCount('gowns')
            ->latest()
            ->paginate(12);
            
        return view('public.shops.index', compact('shops'));
    }
    
    public function show(Shop $shop)
    {
        if ($shop->status !== 'active') {
            abort(404);
        }
        
        $gowns = $shop->gowns()
            ->where('is_available', true)
            ->with('category')
            ->latest()
            ->paginate(12);
            
        return view('public.shops.show', compact('shop', 'gowns'));
    }
}