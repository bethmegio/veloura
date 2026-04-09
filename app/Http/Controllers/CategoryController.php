<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Gown;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('gowns')->get();
        return view('public.categories.index', compact('categories'));
    }
    
    public function show(Category $category)
    {
        $gowns = Gown::where('category_id', $category->id)
            ->where('is_available', true)
            ->with('shop')
            ->latest()
            ->paginate(12);
            
        return view('public.categories.show', compact('category', 'gowns'));
    }
}