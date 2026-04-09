@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Booking #{{ $booking->booking_number }}</h1>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Gown Details</h5>
                    <p><strong>Name:</strong> {{ $booking->gown->name }}</p>
                    <p><strong>Shop:</strong> {{ $booking->gown->shop->shop_name }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>Booking Details</h5>
                    <p><strong>Start Date:</strong> {{ $booking->start_date->format('M d, Y') }}</p>
                    <p><strong>End Date:</strong> {{ $booking->end_date->format('M d, Y') }}</p>
                    <p><strong>Total Days:</strong> {{ $booking->total_days }}</p>
                    <p><strong>Subtotal:</strong> ${{ number_format($booking->subtotal, 2) }}</p>
                    <p><strong>Deposit:</strong> ${{ number_format($booking->deposit_amount, 2) }}</p>
                    <p><strong>Total:</strong> ${{ number_format($booking->total_amount, 2) }}</p>
                    <p><strong>Status:</strong> {{ $booking->status }}</p>
                    @if($booking->pickup_date)
                    <p><strong>Pickup Date:</strong> {{ $booking->pickup_date->format('M d, Y') }}</p>
                    @endif
                    @if($booking->return_date)
                    <p><strong>Return Date:</strong> {{ $booking->return_date->format('M d, Y') }}</p>
                    @endif
                    @if($booking->special_requests)
                    <p><strong>Special Requests:</strong> {{ $booking->special_requests }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @if($booking->canBeCancelled())
    <div class="mt-3">
        <form action="{{ route('customer.bookings.cancel', $booking) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Cancel Booking</button>
        </form>
    </div>
    @endif
    
    @if($booking->canBeReviewed())
    <div class="mt-3">
        <a href="{{ route('customer.reviews.create', $booking) }}" class="btn btn-primary">Write a Review</a>
    </div>
    @endif
</div>
@endsection