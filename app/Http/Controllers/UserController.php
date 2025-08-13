<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
{
    $users = User::with('roles', 'permissions')->paginate(8);
    $departments = Department::all();  // Fetch departments
    return view('users.index', compact('users', 'departments'));
}

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('users.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
    $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => 'required|string|email|max:255|unique:users,email',
        'password'      => 'required|string|min:6|confirmed',
        'role'          => 'required|string|exists:roles,name',
        'department_id' => 'nullable|exists:departments,id',
        'phone'         => 'nullable|string|max:20',
        'status'        => 'nullable|in:active,inactive',
        'permissions'   => 'array',
    ]);

    $user = User::create([
        'name'          => $request->name,
        'email'         => $request->email,
        'role'          => $request->role,
        'department_id' => $request->department_id,
        'phone'         => $request->phone,
        'status'        => $request->status,
        'password'      => Hash::make($request->password),
    ]);

    $user->assignRole($request->role);
    $user->syncPermissions($request->permissions ?? []);

    return redirect()->route('users.index')
        ->with('success', 'User created successfully with assigned permissions.');
}

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
    $roles = Role::all();
    $permissions = Permission::all();
    $departments = Department::all();
    return view('users.edit', compact('user', 'roles', 'permissions', 'departments'));
}

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password'      => 'nullable|string|min:6|confirmed',
            'role'          => 'required|string|exists:roles,name',
            'department_id' => 'nullable|exists:departments,id',  // Add validation
            'phone'         => 'nullable|string|max:20',          // Optional validation for phone
            'status'        => 'nullable|in:active,inactive',     // Add status validation
            'permissions'   => 'array',
        ]);

        $user->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'role'          => $request->role,
            'department_id' => $request->department_id,  // Add this
            'phone'         => $request->phone,          // Add this
            'status'        => $request->status,         // Add this
            'password'      => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        // Update role
        $user->syncRoles([$request->role]);

        // Update permissions
        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('users.index')
            ->with('success', 'User and permissions updated successfully.');
    }

//Update Permissions
public function updatePermissions(Request $request, User $user)
{
    $request->validate([
        'permissions' => 'array'
    ]);

    // Sync the user's permissions
    $user->syncPermissions($request->permissions ?? []);

    return back()->with('success', 'Permissions updated successfully.');
}


//Get user permissions
public function getPermissions(User $user)
{
    return response()->json([
        'permissions' => $user->permissions->pluck('name')
    ]);
}
    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
