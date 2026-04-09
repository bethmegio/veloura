@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Shops Management</h2>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Owner</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shops as $shop)
                    <tr>
                        <td>{{ $shop->shop_name }}</td>
                        <td>{{ $shop->owner->name }}</td>
                        <td>{{ $shop->email }}</td>
                        <td>{{ $shop->phone }}</td>
                        <td>
                            <span class="badge badge-{{ $shop->status === 'active' ? 'success' : 'warning' }}">
                                {{ $shop->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.shops.show', $shop) }}" class="btn btn-sm btn-info">View</a>
                            @if($shop->status === 'pending')
                            <form action="{{ route('admin.shops.approve', $shop) }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>
                            @endif
                            <form action="{{ route('admin.shops.toggle', $shop) }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning">Toggle</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No shops found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $shops->links() }}
        </div>
    </div>
</div>
@endsection