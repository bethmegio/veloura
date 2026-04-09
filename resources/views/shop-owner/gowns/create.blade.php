@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Gown</h1>
    
    <form method="POST" action="{{ route('shop.gowns.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Price per Day</label>
                    <input type="number" name="price_per_day" class="form-control" step="0.01" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Deposit</label>
                    <input type="number" name="deposit" class="form-control" step="0.01">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Size</label>
                    <input type="text" name="size" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Color</label>
                    <input type="text" name="color" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Material</label>
                    <input type="text" name="material" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Condition</label>
                    <select name="condition" class="form-control" required>
                        <option value="excellent">Excellent</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock_quantity" class="form-control" required min="1">
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label>Images (optional)</label>
            <input type="file" name="images[]" class="form-control" multiple>
        </div>
        
        <button type="submit" class="btn btn-primary">Create Gown</button>
    </form>
</div>
@endsection