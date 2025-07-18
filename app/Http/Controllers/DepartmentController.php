<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ggccdept = Department::all();
         $users = User::all();
        return view('departments.index', compact('ggccdept', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
         $users = User::all();
        return view('items.create', compact('departments', 'users'));

       
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        // Create the department
       $result = Department::create([
            'name' => $request->name,
            'description' => $request->description,
            'manager_id' => $request->manager_id,
        ]);

        // Redirect or return a response

        return redirect()->route('departments.index')->with('success', 'Department added successfully.');
        // For debugging purposes, you can uncomment the line below
        //
    //    return dd($request->all());
    }

    /**
     * Display the specified resource.
     */
public function show(string $id)
{
    $department = Department::findOrFail($id);

    return response()->json([
        'id' => $department->id,
        'name' => $department->name,
        'description' => $department->description,
        'manager_id' => $department->manager_id,
    ]);
}

    /**
     * Show the form for editing the specified resource.
     */
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'manager_id' => 'nullable|exists:users,id',
    ]);

    $department = Department::findOrFail($id);
    $department->update([
        'name' => $request->name,
        'description' => $request->description,
        'manager_id' => $request->manager_id,
    ]);

    return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::find($id);

    if (!$department) {
        return redirect()->route('departments.index')->with('error', 'Department not found.');
    }

    $department->delete();

    return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
