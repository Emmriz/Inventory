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
        return view('users.index', compact('users'));
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
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,staff',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    // Update a user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|in:admin,staff',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    // (Optional) Get users by department
    public function byDepartment($departmentId)
    {
        $users = User::where('department_id', $departmentId)->get();
        return response()->json($users);
    }
}
