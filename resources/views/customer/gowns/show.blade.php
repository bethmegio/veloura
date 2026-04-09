@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $gown->name }}</h1>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <p><strong>Shop:</strong> {{ $gown->shop->shop_name }}</p>
                    <p><strong>Category:</strong> {{ $gown->category->name }}</p>
                    <p><strong>Price:</strong> ${{ number_format($gown->price_per_day, 2) }}/day</p>
                    <p><strong>Deposit:</strong> ${{ number_format($gown->deposit ?? 0, 2) }}</p>
                    <p><strong>Size:</strong> {{ $gown->size }}</p>
                    <p><strong>Color:</strong> {{ $gown->color }}</p>
                    <p><strong>Material:</strong> {{ $gown->material }}</p>
                    <p><strong>Condition:</strong> {{ $gown->condition }}</p>
                    <p><strong>Description:</strong> {{ $gown->description }}</p>
                    <p><strong>Rating:</strong> {{ number_format($gown->average_rating, 1) }}/5 ({{ $gown->reviews_count }} reviews)</p>
                    <a href="{{ route('customer.bookings.create', $gown) }}" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <h4>Reviews</h4>
            @forelse($gown->reviews as $review)
            <div class="border-bottom py-2">
                <strong>{{ $review->user->name }}</strong> - {{ $review->rating }}/5
                <p>{{ $review->comment }}</p>
            </div>
            @empty
            <p>No reviews yet</p>
            @endforelse
        </div>
    </div>
    
    <h4 class="mt-4">Similar Gowns</h4>
    <div class="row">
        @forelse($similarGowns as $g)
        <div class="col-md-3 mb-2">
            <div class="card">
                <div class="card-body">
                    <h6>{{ $g->name }}</h6>
                    <p>${{ number_format($g->price_per_day, 2) }}/day</p>
                    <a href="{{ route('gowns.show', $g->slug) }}">View</a>
                </div>
            </div>
        </div>
        @empty
        @endforelse
    </div>
</div>
@endsection