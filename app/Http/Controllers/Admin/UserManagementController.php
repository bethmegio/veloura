<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    public function index(Request $request)
    {
        $role = $request->get('role', 'all');
        
        $query = User::query();
        
        if ($role !== 'all') {
            $query->where('role', $role);
        }
        
        $users = $query->latest()->paginate(15);
        
        return view('admin.users.index', compact('users', 'role'));
    }
    
    public function show(User $user)
    {
        if ($user->role === 'shop_owner') {
            $user->load('shop');
        }
        
        $user->load(['bookings' => function($query) {
            $query->latest()->limit(10);
        }]);
        
        return view('admin.users.show', compact('user'));
    }
    
    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,shop_owner,customer'
        ]);
        
        // FIXED: Use Auth::id() directly without storing in variable
        if (Auth::check() && $user->id === Auth::id()) {
            return back()->with('error', 'You cannot change your own role!');
        }
        
        // Prevent removing the last admin
        if ($user->role === 'admin' && $request->role !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Cannot remove the last admin user!');
            }
        }
        
        $user->update(['role' => $request->role]);
        
        return back()->with('success', 'User role updated successfully!');
    }
    
    public function destroy(User $user)
    {
        // FIXED: Use Auth::id() directly without storing in variable
        if (Auth::check() && $user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete yourself!');
        }
        
        // Prevent deleting the last admin
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Cannot delete the last admin user!');
            }
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}