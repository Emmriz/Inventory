<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // List all users
    public function index()
{
    $users = User::with('department')->get();
    $departments = Department::all(); // Add this line
    
    return view('users.index', compact('users', 'departments'));
}


    // Show a single user
    public function show($id)
    {
        $user = User::with(['department', 'managedDepartment'])->findOrFail($id);
        return response()->json($user);
    }

    // Store a new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'department_id' => 'nullable|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        // Remove password_confirmation from the data to be saved
        unset($validated['password_confirmation']);

        $user = User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    // Update a user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|nullable|string|min:8|confirmed',
            'role' => 'sometimes|in:admin,user',
            'department_id' => 'nullable|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            'status' => 'sometimes|in:active,inactive',
        ]);

        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        // Remove password_confirmation from the data to be saved
        unset($validated['password_confirmation']);

        $user->update($validated);

        if ($request->expectsJson()) {
            return response()->json($user);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
    //Manage user permissions
    public function updatePermissions(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Validate the incoming permissions array
    $validated = $request->validate([
        'permissions' => 'array|required',
        'permissions.*' => 'string'
    ]);

    // If you're using Spatie Laravel Permission:
    $user->syncPermissions($validated['permissions']);

    return redirect()->back()->with('success', 'Permissions updated successfully.');
}

    // (Optional) Get users by department
    public function byDepartment($departmentId)
    {
        $users = User::where('department_id', $departmentId)->get();
        return response()->json($users);
    }

    // Update last login timestamp
    public function updateLastLogin($id)
    {
        $user = User::findOrFail($id);
        $user->update(['last_login' => now()]);
        
        return response()->json(['message' => 'Last login updated successfully']);
    }
}
