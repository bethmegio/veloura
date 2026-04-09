@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Bookings</h1>
    
    <form method="GET" class="mb-3">
        <select name="status" onchange="this.form.submit()" class="form-control" style="width: 200px;">
            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All</option>
            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ $status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="rented" {{ $status == 'rented' ? 'selected' : '' }}>Rented</option>
            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
    </form>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Gown</th>
                <th>Dates</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr>
                <td>{{ $booking->booking_number }}</td>
                <td>{{ $booking->customer->name }}</td>
                <td>{{ $booking->gown->name }}</td>
                <td>{{ $booking->start_date->format('M d') }} - {{ $booking->end_date->format('M d') }}</td>
                <td>${{ number_format($booking->total_amount, 2) }}</td>
                <td>{{ $booking->status }}</td>
                <td>
                    <a href="{{ route('shop.bookings.show', $booking) }}" class="btn btn-sm btn-info">View</a>
                    @if($booking->status == 'pending')
                    <form action="{{ route('shop.bookings.confirm', $booking) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Confirm</button>
                    </form>
                    @endif
                    @if($booking->status == 'confirmed')
                    <form action="{{ route('shop.bookings.pickup', $booking) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">Pickup</button>
                    </form>
                    @endif
                    @if($booking->status == 'rented')
                    <form action="{{ route('shop.bookings.return', $booking) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-info">Return</button>
                    </form>
                    @endif
                    @if($booking->status == 'returned')
                    <form action="{{ route('shop.bookings.complete', $booking) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Complete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No bookings found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $bookings->links() }}
</div>
@endsection