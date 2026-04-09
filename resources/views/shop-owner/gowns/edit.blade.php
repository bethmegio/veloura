@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Gown</h1>
    
    <form method="POST" action="{{ route('shop.gowns.update', $gown) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $gown->name }}" required>
        </div>
        
        <div class="form-group">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $gown->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4" required>{{ $gown->description }}</textarea>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Price per Day</label>
                    <input type="number" name="price_per_day" class="form-control" step="0.01" value="{{ $gown->price_per_day }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Deposit</label>
                    <input type="number" name="deposit" class="form-control" step="0.01" value="{{ $gown->deposit }}">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Size</label>
                    <input type="text" name="size" class="form-control" value="{{ $gown->size }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Color</label>
                    <input type="text" name="color" class="form-control" value="{{ $gown->color }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Material</label>
                    <input type="text" name="material" class="form-control" value="{{ $gown->material }}">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Condition</label>
                    <select name="condition" class="form-control" required>
                        <option value="excellent" {{ $gown->condition == 'excellent' ? 'selected' : '' }}>Excellent</option>
                        <option value="good" {{ $gown->condition == 'good' ? 'selected' : '' }}>Good</option>
                        <option value="fair" {{ $gown->condition == 'fair' ? 'selected' : '' }}>Fair</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock_quantity" class="form-control" value="{{ $gown->stock_quantity }}" required min="1">
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Gown</button>
    </form>
</div>
@endsection