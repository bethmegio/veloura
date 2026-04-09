<?php

namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;
use App\Models\Gown;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GownController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        $gowns = $shop->gowns()->with('category')->latest()->paginate(12);
        
        return view('shop-owner.gowns.index', compact('gowns'));
    }
    
    public function create()
    {
        $categories = Category::all();
        return view('shop-owner.gowns.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price_per_day' => 'required|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'material' => 'nullable|string',
            'condition' => 'required|in:excellent,good,fair',
            'stock_quantity' => 'required|integer|min:1',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);
        
        $data = $request->except('images');
        $data['slug'] = Str::slug($request->name . '-' . uniqid());
        $data['shop_id'] = Auth::user()->shop->id;
        $data['is_available'] = true;
        
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('gowns', 'public');
            }
            $data['images'] = json_encode($images);
        }
        
        $gown = Gown::create($data);
        
        return redirect()->route('shop.gowns.index')
            ->with('success', 'Gown added successfully!');
    }
    
    public function edit(Gown $gown)
    {
        $this->authorizeShopOwner($gown);
        
        $categories = Category::all();
        return view('shop-owner.gowns.edit', compact('gown', 'categories'));
    }
    
    public function update(Request $request, Gown $gown)
    {
        $this->authorizeShopOwner($gown);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price_per_day' => 'required|numeric|min:0',
            'deposit' => 'nullable|numeric|min:0',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
            'material' => 'nullable|string',
            'condition' => 'required|in:excellent,good,fair',
            'stock_quantity' => 'required|integer|min:1',
        ]);
        
        $gown->update($request->all());
        
        return redirect()->route('shop.gowns.index')
            ->with('success', 'Gown updated successfully!');
    }
    
    public function toggleAvailability(Gown $gown)
    {
        $this->authorizeShopOwner($gown);
        
        $gown->update(['is_available' => !$gown->is_available]);
        
        $status = $gown->is_available ? 'available' : 'unavailable';
        return back()->with('success', "Gown is now {$status}");
    }
    
    public function destroy(Gown $gown)
    {
        $this->authorizeShopOwner($gown);
        
        $hasActiveBookings = $gown->bookings()
            ->whereNotIn('status', ['completed', 'cancelled', 'returned'])
            ->exists();
            
        if ($hasActiveBookings) {
            return back()->with('error', 'Cannot delete gown with active bookings!');
        }
        
        $gown->delete();
        
        return redirect()->route('shop.gowns.index')
            ->with('success', 'Gown deleted successfully!');
    }
    
    private function authorizeShopOwner($gown)
    {
        if ($gown->shop_id !== Auth::user()->shop->id) {
            abort(403, 'Unauthorized action.');
        }
    }
}