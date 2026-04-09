@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Write a Review</h1>
    
    <form method="POST" action="{{ route('customer.reviews.store', $booking) }}" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label>Gown</label>
            <p>{{ $booking->gown->name }}</p>
        </div>
        
        <div class="form-group">
            <label>Rating</label>
            <select name="rating" class="form-control" required>
                <option value="">Select rating</option>
                <option value="5">5 - Excellent</option>
                <option value="4">4 - Very Good</option>
                <option value="3">3 - Good</option>
                <option value="2">2 - Fair</option>
                <option value="1">1 - Poor</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Comment</label>
            <textarea name="comment" class="form-control" rows="4" required minlength="10"></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
</div>
@endsection