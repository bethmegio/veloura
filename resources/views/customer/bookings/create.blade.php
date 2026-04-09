@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Book Gown: {{ $gown->name }}</h1>
    
    <form method="POST" action="{{ route('customer.bookings.store') }}">
        @csrf
        <input type="hidden" name="gown_id" value="{{ $gown->id }}">
        
        <div class="form-group">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" required min="{{ date('Y-m-d') }}">
        </div>
        
        <div class="form-group">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label>Special Requests (optional)</label>
            <textarea name="special_requests" class="form-control" rows="3"></textarea>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h5>Price Breakdown</h5>
                <p><strong>Price per day:</strong> ${{ number_format($gown->price_per_day, 2) }}</p>
                <p><strong>Deposit:</strong> ${{ number_format($gown->deposit ?? 0, 2) }}</p>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary mt-3">Confirm Booking</button>
    </form>
</div>
@endsection