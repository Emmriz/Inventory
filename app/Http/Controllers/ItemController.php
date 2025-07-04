<?php

namespace App\Http\Controllers;
use App\Models\Department;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    
    $departments = Department::all(); 
    $items = Item::all(); // <-- Add this

    return view('items.index', compact('departments', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    $departments = Department::all();
    $items = Item::all(); // Add this line
    return view('items.index', compact('departments', 'items'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'department_id' => 'required|exists:departments,id',
            'quantity' => 'required|integer|min:1',
            'min_quantity' => 'required|integer',
        ]);

        // Create the item
        Item::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'department_id' => $request->department_id,
            'quantity' => $request->quantity,
            'min_quantity' => $request->min_quantity,
            'created_by' => optional(Auth::user())->id, // Handles unauthenticated users safely
            'updated_by' => optional(Auth::user())->id, // Handles unauthenticated users safely
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirect or return a response
        return redirect()->route('items.index');
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
