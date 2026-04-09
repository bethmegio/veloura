@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Set Up Your Shop</h1>
    
    <form method="POST" action="{{ route('shop.setup.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label>Shop Name</label>
            <input type="text" name="shop_name" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
        
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>
        
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label>Logo (optional)</label>
            <input type="file" name="logo" class="form-control">
        </div>
        
        <button type="submit" class="btn btn-primary">Create Shop</button>
    </form>
</div>
@endsection