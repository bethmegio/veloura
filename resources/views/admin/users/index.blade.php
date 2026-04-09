@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>User Management</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <!-- Role Filter -->
                    <div class="mb-3">
                        <form method="GET" class="form-inline">
                            <label class="mr-2">Filter by role:</label>
                            <select name="role" class="form-control w-auto" onchange="this.form.submit()">
                                <option value="all" {{ $role == 'all' ? 'selected' : '' }}>All Users</option>
                                <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admins</option>
                                <option value="shop_owner" {{ $role == 'shop_owner' ? 'selected' : '' }}>Shop Owners</option>
                                <option value="customer" {{ $role == 'customer' ? 'selected' : '' }}>Customers</option>
                            </select>
                        </form>
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge badge-{{ 
                                            $user->role == 'admin' ? 'danger' : 
                                            ($user->role == 'shop_owner' ? 'warning' : 'info') 
                                        }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">View</a>
                                        
                                        @if(auth()->id() !== $user->id)
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#roleModal{{ $user->id }}">
                                                Change Role
                                            </button>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Role Change Modal -->
                                <div class="modal fade" id="roleModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.users.role', $user) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Change Role for {{ $user->name }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Select New Role</label>
                                                        <select name="role" class="form-control" required>
                                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                            <option value="shop_owner" {{ $user->role == 'shop_owner' ? 'selected' : '' }}>Shop Owner</option>
                                                            <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Role</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No users found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection