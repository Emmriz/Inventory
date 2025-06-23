<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ggccdept = Department::all();
        return view('departments.index', compact('ggccdept'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        return view('items.create', compact('departments'));
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

        

        // dd($result);

        // Department::create($request->all());

        // Redirect or return a response
        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
        
        // For debugging purposes, you can uncomment the line below
        //
       return dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
