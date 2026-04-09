@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Gowns</h1>
    <a href="{{ route('shop.gowns.create') }}" class="btn btn-primary mb-3">Add New Gown</a>
    
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price/Day</th>
                <th>Stock</th>
                <th>Available</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gowns as $gown)
            <tr>
                <td>{{ $gown->name }}</td>
                <td>{{ $gown->category->name }}</td>
                <td>${{ number_format($gown->price_per_day, 2) }}</td>
                <td>{{ $gown->stock_quantity }}</td>
                <td>{{ $gown->is_available ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('shop.gowns.edit', $gown) }}" class="btn btn-sm btn-info">Edit</a>
                    <form action="{{ route('shop.gowns.toggle', $gown) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning">{{ $gown->is_available ? 'Disable' : 'Enable' }}</button>
                    </form>
                    <form action="{{ route('shop.gowns.destroy', $gown) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No gowns yet</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $gowns->links() }}
</div>
@endsection