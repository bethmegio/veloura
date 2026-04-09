@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $category->name }} Gowns</h1>
    
    <div class="row">
        @forelse($gowns as $gown)
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5>{{ $gown->name }}</h5>
                    <p><strong>Shop:</strong> {{ $gown->shop->shop_name }}</p>
                    <p><strong>Price:</strong> ${{ number_format($gown->price_per_day, 2) }}/day</p>
                    <a href="{{ route('gowns.show', $gown->slug) }}" class="btn btn-sm btn-info">View</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-md-12">
            <p>No gowns in this category</p>
        </div>
        @endforelse
    </div>
    
    {{ $gowns->links() }}
</div>
@endsection