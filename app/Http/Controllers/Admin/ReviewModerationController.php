<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewModerationController extends Controller
{
    public function pending()
    {
        $reviews = Review::with(['user', 'gown'])
            ->where('is_approved', false)
            ->latest()
            ->paginate(20);
            
        return view('admin.reviews.pending', compact('reviews'));
    }
    
    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        
        return back()->with('success', 'Review approved successfully!');
    }
    
    public function reject(Review $review)
    {
        $review->delete();
        
        return back()->with('success', 'Review rejected and removed!');
    }
    
    public function index()
    {
        $reviews = Review::with(['user', 'gown'])
            ->where('is_approved', true)
            ->latest()
            ->paginate(20);
            
        return view('admin.reviews.index', compact('reviews'));
    }
}