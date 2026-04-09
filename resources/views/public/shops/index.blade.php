@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Shops</h1>
    
    <div class="row">
        @forelse($shops as $shop)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5>{{ $shop->shop_name }}</h5>
                    <p>{{ $shop->gowns_count }} gowns</p>
                    <a href="{{ route('shops.show', $shop) }}" class="btn btn-primary">View Shop</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-md-12">
            <p>No shops available</p>
        </div>
        @endforelse
    </div>
    
    {{ $shops->links() }}
</div>
@endsection