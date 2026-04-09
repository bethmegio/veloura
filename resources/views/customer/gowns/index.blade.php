@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Browse Gowns</h1>
    
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-2">
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-control">
                    <option value="latest">Latest</option>
                    <option value="price_low">Price: Low to High</option>
                    <option value="price_high">Price: High to Low</option>
                    <option value="popular">Most Popular</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>
    
    <div class="row">
        @forelse($gowns as $gown)
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5>{{ $gown->name }}</h5>
                    <p><strong>Shop:</strong> {{ $gown->shop->shop_name }}</p>
                    <p><strong>Category:</strong> {{ $gown->category->name }}</p>
                    <p><strong>Price:</strong> ${{ number_format($gown->price_per_day, 2) }}/day</p>
                    <a href="{{ route('gowns.show', $gown->slug) }}" class="btn btn-sm btn-info">View</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-md-12">
            <p>No gowns found</p>
        </div>
        @endforelse
    </div>
    
    {{ $gowns->links() }}
</div>
@endsection