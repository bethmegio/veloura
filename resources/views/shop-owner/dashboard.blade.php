@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Shop Owner Dashboard</h1>
    <h3>Shop: {{ $shop->shop_name }}</h3>
    
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Gowns</h5>
                    <h2>{{ $stats['total_gowns'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Available</h5>
                    <h2>{{ $stats['available_gowns'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Bookings</h5>
                    <h2>{{ $stats['total_bookings'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Revenue</h5>
                    <h2>${{ number_format($stats['total_revenue'], 2) }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Recent Bookings</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Gown</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBookings as $booking)
                            <tr>
                                <td>{{ $booking->booking_number }}</td>
                                <td>{{ $booking->customer->name }}</td>
                                <td>{{ $booking->gown->name }}</td>
                                <td>{{ $booking->status }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Popular Gowns</div>
                <div class="card-body">
                    @foreach($popularGowns as $gown)
                    <div class="border-bottom py-2">
                        <strong>{{ $gown->name }}</strong> - {{ $gown->bookings_count }} bookings
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection