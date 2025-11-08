<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUser = auth()->user();
        
        // Filter users based on role level and ownership
        $users = User::with('role')
            ->whereHas('role', function($query) use ($currentUser) {
                if ($currentUser->role) {
                    $query->where('level', '<', $currentUser->role->level);
                }
            })
            ->when($currentUser->role && $currentUser->role->level == 8, function($query) use ($currentUser) {
                // Level 8 can only see users they created
                $query->where('created_by', $currentUser->id);
            })
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only show roles that current user can assign
        $roles = auth()->user()->getAssignableRoles();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        // Check if current user can assign the selected role
        $role = Role::findOrFail($validated['role_id']);
        if (!auth()->user()->canAssignRole($role)) {
            abort(403, 'You cannot assign this role level.');
        }

        $validated['password'] = Hash::make($validated['password']);
        
        // Set created_by to current user
        $validated['created_by'] = auth()->id();

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Check if current user can manage this user
        if (!auth()->user()->canManage($user)) {
            abort(403, 'You cannot view this user.');
        }

        $user->load('role');

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Check if current user can manage this user
        if (!auth()->user()->canManage($user)) {
            abort(403, 'You cannot edit this user.');
        }

        // Only show roles that current user can assign
        $roles = auth()->user()->getAssignableRoles();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Check if current user can manage this user
        if (!auth()->user()->canManage($user)) {
            abort(403, 'You cannot edit this user.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        // Check if current user can assign the selected role
        $role = Role::findOrFail($validated['role_id']);
        if (!auth()->user()->canAssignRole($role)) {
            abort(403, 'You cannot assign this role level.');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Check if current user can manage this user
        if (!auth()->user()->canManage($user)) {
            abort(403, 'You cannot delete this user.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Show the form for resetting user password.
     */
    public function resetPasswordForm(User $user)
    {
        // Check if current user can manage this user
        if (!auth()->user()->canManage($user)) {
            abort(403, 'You cannot reset password for this user.');
        }

        return view('admin.users.reset-password', compact('user'));
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user)
    {
        // Check if current user can manage this user
        if (!auth()->user()->canManage($user)) {
            abort(403, 'You cannot reset password for this user.');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Password reset successfully.');
    }
}
