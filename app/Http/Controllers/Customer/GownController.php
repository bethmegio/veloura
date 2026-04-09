<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Gown;
use App\Models\Category;
use App\Models\Shop;
use Illuminate\Http\Request;

class GownController extends Controller
{
    public function index(Request $request)
    {
        $query = Gown::with(['shop', 'category'])
            ->where('is_available', true);
            
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        if ($request->has('shop') && $request->shop != '') {
            $query->where('shop_id', $request->shop);
        }
        
        if ($request->has('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }
        
        if ($request->has('size') && $request->size != '') {
            $query->where('size', $request->size);
        }
        
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price_per_day', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price_per_day', 'desc');
                break;
            case 'popular':
                $query->withCount('bookings')->orderBy('bookings_count', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $gowns = $query->paginate(12);
        
        $categories = Category::withCount('gowns')->get();
        $shops = Shop::where('status', 'active')->withCount('gowns')->get();
        $sizes = Gown::where('is_available', true)->distinct()->pluck('size');
        
        return view('customer.gowns.index', compact('gowns', 'categories', 'shops', 'sizes'));
    }
    
    public function show($slug)
    {
        $gown = Gown::with(['shop', 'category', 'reviews' => function($query) {
                $query->where('is_approved', true)->latest();
            }, 'reviews.user'])
            ->where('slug', $slug)
            ->firstOrFail();
            
        $similarGowns = Gown::where('category_id', $gown->category_id)
            ->where('id', '!=', $gown->id)
            ->where('is_available', true)
            ->limit(4)
            ->get();
            
        return view('customer.gowns.show', compact('gown', 'similarGowns'));
    }
    
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $gowns = Gown::with(['shop', 'category'])
            ->where('is_available', true)
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('shop', function($q) use ($search) {
                        $q->where('shop_name', 'like', "%{$search}%");
                    });
            })
            ->paginate(12);
            
        return view('customer.gowns.search', compact('gowns', 'search'));
    }
}