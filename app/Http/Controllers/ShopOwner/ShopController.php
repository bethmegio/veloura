<?php

namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function setup()
    {
        if (Auth::user()->shop) {
            return redirect()->route('shop.dashboard')
                ->with('info', 'Your shop is already set up!');
        }
        
        return view('shop-owner.shop.setup');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255|unique:shops',
            'description' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:shops',
            'logo' => 'nullable|image|max:2048',
        ]);
        
        $data = $request->all();
        $data['slug'] = Str::slug($request->shop_name);
        $data['owner_id'] = Auth::id();
        $data['status'] = 'pending';
        
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }
        
        $shop = Shop::create($data);
        
        return redirect()->route('shop.dashboard')
            ->with('success', 'Shop created! Waiting for admin approval.');
    }
    
    public function edit()
    {
        $shop = Auth::user()->shop;
        
        if (!$shop) {
            return redirect()->route('shop.setup');
        }
        
        return view('shop-owner.shop.edit', compact('shop'));
    }
    
    public function update(Request $request)
    {
        $shop = Auth::user()->shop;
        
        $request->validate([
            'shop_name' => 'required|string|max:255|unique:shops,shop_name,' . $shop->id,
            'description' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'logo' => 'nullable|image|max:2048',
        ]);
        
        $data = $request->except('logo');
        
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }
        
        $shop->update($data);
        
        return back()->with('success', 'Shop updated successfully!');
    }
}