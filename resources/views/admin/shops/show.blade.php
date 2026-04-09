@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Shop Details</h2>
            <div class="card mt-3">
                <div class="card-body">
                    <h3>{{ $shop->shop_name }}</h3>
                    <p><strong>Owner:</strong> {{ $shop->owner->name }}</p>
                    <p><strong>Email:</strong> {{ $shop->email }}</p>
                    <p><strong>Phone:</strong> {{ $shop->phone }}</p>
                    <p><strong>Address:</strong> {{ $shop->address }}</p>
                    <p><strong>Description:</strong> {{ $shop->description }}</p>
                    <p><strong>Status:</strong> <span class="badge badge-{{ $shop->status === 'active' ? 'success' : 'warning' }}">{{ $shop->status }}</span></p>
                </div>
            </div>
            
            <h4 class="mt-4">Gowns ({{ $shop->gowns->count() }})</h4>
            <table class="table mt-2">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price/Day</th>
                        <th>Available</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shop->gowns as $gown)
                    <tr>
                        <td>{{ $gown->name }}</td>
                        <td>{{ $gown->category->name }}</td>
                        <td>${{ number_format($gown->price_per_day, 2) }}</td>
                        <td>{{ $gown->is_available ? 'Yes' : 'No' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">No gowns</td></tr>
                    @endforelse
                </tbody>
            </table>
            
            <h4 class="mt-4">Statistics</h4>
            <p><strong>Total Bookings:</strong> {{ $totalBookings }}</p>
            <p><strong>Total Revenue:</strong> ${{ number_format($totalRevenue, 2) }}</p>
        </div>
    </div>
</div>
@endsection