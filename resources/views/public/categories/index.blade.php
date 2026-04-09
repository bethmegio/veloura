@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Categories</h1>
    
    <div class="row">
        @forelse($categories as $category)
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5>{{ $category->name }}</h5>
                    <p>{{ $category->gowns_count }} gowns</p>
                    <a href="{{ route('categories.show', $category) }}" class="btn btn-primary">View</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-md-12">
            <p>No categories found</p>
        </div>
        @endforelse
    </div>
</div>
@endsection