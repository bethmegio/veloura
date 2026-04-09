@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Customer Dashboard</h4>
                </div>
                <div class="card-body">
                    <h3>Welcome back, {{ Auth::user()->name }}!</h3>
                    <p>Here's an overview of your gown rental activity.</p>
                    
                    <!-- Statistics Cards -->
                    <div class="row mt-4 mb-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <h5 class="card-title">Total Bookings</h5>
                                    <h2 class="mb-0">{{ $stats['total_bookings'] }}</h2>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <h5 class="card-title">Active Bookings</h5>
                                    <h2 class="mb-0">{{ $stats['active_bookings'] }}</h2>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card text-white bg-info">
                                <div class="card-body">
                                    <h5 class="card-title">Completed Bookings</h5>
                                    <h2 class="mb-0">{{ $stats['completed_bookings'] }}</h2>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body">
                                    <h5 class="card-title">Pending Bookings</h5>
                                    <h2 class="mb-0">{{ $stats['pending_bookings'] }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Bookings -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Recent Bookings</h5>
                        </div>
                        <div class="card-body">
                            @if($recentBookings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Booking #</th>
                                                <th>Gown</th>
                                                <th>Shop</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Total Amount</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentBookings as $booking)
                                            <tr>
                                                <td>{{ $booking->booking_number }}</td>
                                                <td>{{ $booking->gown->name }}</td>
                                                <td>{{ $booking->gown->shop->name }}</td>
                                                <td>{{ $booking->start_date->format('M d, Y') }}</td>
                                                <td>{{ $booking->end_date->format('M d, Y') }}</td>
                                                <td>₱{{ number_format($booking->total_amount, 2) }}</td>
                                                <td>
                                                    <span class="badge badge-{{ 
                                                        $booking->status == 'pending' ? 'warning' :
                                                        ($booking->status == 'confirmed' ? 'info' :
                                                        ($booking->status == 'rented' ? 'primary' :
                                                        ($booking->status == 'completed' ? 'success' : 'danger')))
                                                    }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('customer.bookings.show', $booking) }}" 
                                                       class="btn btn-sm btn-primary">View</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No bookings yet.</p>
                                <a href="{{ route('customer.gowns.index') }}" class="btn btn-primary">
                                    Browse Gowns
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Pending Reviews -->
                    @if($pendingReviews->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Pending Reviews</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($pendingReviews as $booking)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6>{{ $booking->gown->name }}</h6>
                                            <p class="text-muted small">Booking #: {{ $booking->booking_number }}</p>
                                            <a href="{{ route('customer.reviews.create', $booking) }}" 
                                               class="btn btn-sm btn-success">
                                                Write a Review
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection