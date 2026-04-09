@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Bookings</h1>
    
    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Gown</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr>
                <td>{{ $booking->booking_number }}</td>
                <td>{{ $booking->gown->name }}</td>
                <td>{{ $booking->start_date->format('M d, Y') }}</td>
                <td>{{ $booking->end_date->format('M d, Y') }}</td>
                <td>${{ number_format($booking->total_amount, 2) }}</td>
                <td>{{ $booking->status }}</td>
                <td>
                    <a href="{{ route('customer.bookings.show', $booking) }}" class="btn btn-sm btn-info">View</a>
                    @if($booking->canBeCancelled())
                    <form action="{{ route('customer.bookings.cancel', $booking) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
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