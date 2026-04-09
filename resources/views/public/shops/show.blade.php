@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $shop->shop_name }}</h1>
    <p>{{ $shop->description }}</p>
    <p><strong>Address:</strong> {{ $shop->address }}</p>
    <p><strong>Phone:</strong> {{ $shop->phone }}</p>
    <p><strong>Email:</strong> {{ $shop->email }}</p>
    
    <h3>Gowns</h3>
    <div class="row">
        @forelse($gowns as $gown)
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5>{{ $gown->name }}</h5>
                    <p><strong>Category:</strong> {{ $gown->category->name }}</p>
                    <p><strong>Price:</strong> ${{ number_format($gown->price_per_day, 2) }}/day</p>
                    <a href="{{ route('gowns.show', $gown->slug) }}" class="btn btn-sm btn-info">View</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-md-12">
            <p>No gowns available</p>
        </div>
        @endforelse
    </div>
    
    {{ $gowns->links() }}
</div>
@endsection